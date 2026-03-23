<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\File;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $description = trim((string) ($row['description'] ?? ''));
            $variantName = trim((string) ($row['variant_name'] ?? 'Default Variant'));
            $price = is_numeric($row['price'] ?? null) ? (float) $row['price'] : 0;
            $stock = is_numeric($row['stock'] ?? null) ? (int) $row['stock'] : 0;

            // Check if product already exists, otherwise create it
            $product = Product::where('name', $name)->first();
            if (!$product) {
                $imageFilename = $this->extractFirstValue($row, ['image', 'product_image', 'image_filename']);
                $imagePath = $this->resolveImagePath($imageFilename);
                
                $product = Product::create([
                    'name' => $name,
                    'description' => $description !== '' ? $description : null,
                    'image' => $imagePath,
                ]);

                $categoryNames = collect(explode(',', (string) ($row['categories'] ?? '')))
                    ->map(fn ($category) => trim($category))
                    ->filter()
                    ->values();

                if ($categoryNames->isNotEmpty()) {
                    $categoryIds = $categoryNames->map(function (string $categoryName) {
                        return Category::firstOrCreate(['name' => $categoryName])->id;
                    })->all();

                    $product->categories()->sync($categoryIds);
                }
            }

            // Always create/add the variant
            $variantImage = $this->extractFirstValue($row, ['variant_image', 'variantimage', 'variant_image_filename']);
            $variantImagePath = $this->resolveImagePath($variantImage);
            
            $product->variants()->create([
                'variant_name' => $variantName !== '' ? $variantName : 'Default Variant',
                'image' => $variantImagePath,
                'price' => max($price, 0),
                'stock' => max($stock, 0),
            ]);

            $this->importedCount++;
        }
    }

    private function extractFirstValue($row, array $keys): string
    {
        foreach ($keys as $key) {
            $value = trim((string) ($row[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    private function resolveImagePath(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $relative = ltrim(str_replace('\\', '/', $value), '/');

        // Already in storage/app/public
        if (Storage::disk('public')->exists($relative)) {
            return $relative;
        }

        // Try in storage/app/public/variants using filename
        $filename = basename($relative);
        $variantRelative = 'variants/' . $filename;
        if (Storage::disk('public')->exists($variantRelative)) {
            return $variantRelative;
        }

        // If file is in public/variants, copy it into storage/app/public/variants
        $publicVariantPath = public_path('variants/' . $filename);
        if (File::exists($publicVariantPath)) {
            if (!Storage::disk('public')->exists($variantRelative)) {
                Storage::disk('public')->put($variantRelative, File::get($publicVariantPath));
            }

            return $variantRelative;
        }

        return null;
    }
}
