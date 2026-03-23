# FLEUR DE PEAU - Skincare E-Commerce Platform

A Laravel-based e-commerce platform for skincare products with comprehensive product, variant, and category management.

## Features Implemented

### 1. Product Management (Admin)
**Requirement FR 2.1**: Admin can add, edit, or delete products.

- **Add Products**: Create new products with name, category, description, base price, and image upload
- **Edit Products**: Update product details and manage product information
- **Delete Products**: Remove products from the catalog (soft delete)
- **Product Listing**: View all products with pagination, category tags, and variant counts
- **Image Upload**: Support for product images (JPEG, PNG, GIF up to 2MB)

**Routes:**
- `GET /admin/products` - List all products
- `GET /admin/products/create` - Product creation form
- `POST /admin/products` - Store new product
- `GET /admin/products/{product}/edit` - Edit product form
- `PATCH /admin/products/{product}` - Update product
- `DELETE /admin/products/{product}` - Delete product

### 2. Product Variant Management (Admin)
**Requirement FR 3.1**: Admin can manage product variants (size, color, stock).

- **Add Variants**: Create multiple variants for each product with size, color, price, and stock levels
- **Edit Variants**: Update variant details inline in the product edit page
- **Delete Variants**: Remove specific variants from products
- **Stock Management**: Track inventory for each variant separately
- **Price Flexibility**: Different prices for different variants

**Routes:**
- `POST /admin/products/{product}/variants` - Add variant
- `PATCH /admin/products/{product}/variants/{variant}` - Update variant
- `DELETE /admin/products/{product}/variants/{variant}` - Delete variant

### 3. Category Management (Admin)
**Requirement FR 3.2**: Admin can add, edit, or delete product categories.

- **Add Categories**: Create new product categories with name and description
- **Edit Categories**: Update category information
- **Delete Categories**: Remove categories (with protection for categories with products)
- **Category Listing**: View all categories with product count
- **Unique Names**: Validation to ensure category names are unique

**Routes:**
- `GET /admin/categories` - List all categories
- `GET /admin/categories/create` - Category creation form
- `POST /admin/categories` - Store new category
- `GET /admin/categories/{category}/edit` - Edit category form
- `PATCH /admin/categories/{category}` - Update category
- `DELETE /admin/categories/{category}` - Delete category

### 4. User Product Browsing
**Requirement FR 2.2**: Users can view and search products by name or category.
**Requirement FR 3.3**: Users can browse products by category.

- **Product Catalog**: Grid view of all products with images, prices, and descriptions
- **Search Functionality**: Search products by name or description using a search bar
- **Category Filter**: Filter products by category using dropdown selector
- **Product Details**: Detailed product page showing all variants and availability
- **Category Navigation**: Browse products by specific category
- **Responsive Design**: Mobile-friendly product grid layout

**Routes:**
- `GET /products` - Product listing with search and category filter
- `GET /products/{product}` - Product detail page
- `GET /categories/{category}/products` - Products by category

## Navigation Updates

### Admin Navigation
Added to the main navigation bar for admin users:
- **Manage Users** - User account management
- **Manage Products** - Product catalog management
- **Manage Categories** - Category organization

### User Navigation
Added to the main navigation bar for regular users:
- **Products** - Browse all products with search and category filters

### Dashboard Cards
Added quick access cards on the dashboard:
- **Admin Dashboard**:
  - Product management card (blue theme)
  - Category management card (emerald theme)
  - Existing user management card (amber theme)
  
- **User Dashboard**:
  - Browse products card (blue theme)

## Database Structure

### Products Table
- `id` - Primary key
- `category_id` - Foreign key to categories
- `name` - Product name
- `description` - Product description (nullable)
- `base_price` - Base price (decimal 10,2)
- `image` - Image path (nullable)
- `timestamps` - Created/updated timestamps
- `deleted_at` - Soft delete timestamp

### Product Variants Table
- `id` - Primary key
- `product_id` - Foreign key to products
- `size` - Size specification (nullable)
- `color` - Color specification (nullable)
- `price` - Variant price (decimal 10,2)
- `stock` - Inventory count (integer)
- `timestamps` - Created/updated timestamps

### Categories Table
- `id` - Primary key
- `name` - Category name (unique)
- `description` - Category description (nullable)
- `timestamps` - Created/updated timestamps

## Models & Relationships

### Product Model
- Belongs to Category
- Has many ProductVariants
- Soft deletes enabled
- Fillable: category_id, name, description, base_price, image

### ProductVariant Model
- Belongs to Product
- Fillable: product_id, size, color, price, stock

### Category Model
- Has many Products
- Fillable: name, description

## Setup Instructions

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Create Admin User**
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

3. **Seed Categories (Optional)**
   ```bash
   php artisan db:seed --class=CategorySeeder
   ```

4. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

5. **Ensure Storage Permissions**
   Make sure the `storage/app/public/products` directory is writable.

## Usage Guide

### For Administrators

1. **Adding Categories**
   - Navigate to "Manage Categories" from the navigation
   - Click "+ Add Category"
   - Fill in category name and description
   - Click "Create Category"

2. **Adding Products**
   - Navigate to "Manage Products" from the navigation
   - Click "+ Add Product"
   - Select a category
   - Fill in product details (name, description, base price)
   - Upload a product image (optional)
   - Click "Create Product"

3. **Managing Variants**
   - Edit a product
   - Scroll to "Product Variants" section
   - Click "+ Add Variant"
   - Fill in size, color, price, and stock
   - Click "Add"
   - Edit or delete variants inline

### For Users

1. **Browsing Products**
   - Click "Products" in the navigation
   - Use the search bar to find products by name or description
   - Use the category dropdown to filter by category
   - Click on any product to view details

2. **Viewing Product Details**
   - Click "View Details" on any product
   - See all available variants with pricing and stock status
   - View related products in the same category

## File Structure

```
app/
├── Http/Controllers/
│   ├── ProductController.php (User-facing)
│   └── Admin/
│       ├── CategoryController.php
│       ├── ProductController.php
│       └── VariantController.php
├── Models/
│   ├── Category.php
│   ├── Product.php
│   └── ProductVariant.php
resources/views/
├── admin/
│   ├── categories/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── products/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── products/
│   ├── index.blade.php
│   └── show.blade.php
├── dashboard.blade.php
└── layouts/
    └── navigation.blade.php
database/
├── migrations/
│   ├── 2026_02_26_010011_create_categories_table.php
│   ├── 2026_02_26_010215_create_products_table.php
│   └── 2026_02_26_010216_create_product_variants_table.php
└── seeders/
    ├── AdminUserSeeder.php
    └── CategorySeeder.php
```

## Design Features

- **Consistent Theme**: Rose/pink color scheme matching FLEUR DE PEAU branding
- **Modern UI**: Rounded cards, smooth transitions, and hover effects
- **Responsive Design**: Mobile-friendly layouts using Tailwind CSS
- **Inline Editing**: Variant management directly within product edit page
- **Visual Feedback**: Success/error messages for all operations
- **Image Support**: Product image uploads with fallback placeholders
- **Stock Indicators**: Visual badges showing stock availability
- **Search UX**: Real-time search with clear results and filtering

## Security & Validation

- **Admin Middleware**: All admin routes protected by admin middleware
- **CSRF Protection**: All forms include CSRF tokens
- **File Validation**: Image uploads validated for type and size
- **Input Validation**: Required fields, unique constraints, and data types validated
- **Soft Deletes**: Products use soft deletes to preserve order history
- **Category Protection**: Categories with products cannot be deleted

## Future Enhancements

- Add shopping cart functionality
- Implement order management
- Add product ratings and reviews
- Bulk product import/export
- Advanced filtering (price range, stock status)
- Product image gallery (multiple images per product)
- Inventory alerts for low stock
- Sales analytics and reports

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
