<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with(['user', 'product'])
            ->latest()
            ->paginate(15);

        return view('admin.ratings.index', compact('ratings'));
    }

    public function destroy(Rating $rating)
    {
        if ($rating->photo_path) {
            Storage::disk('public')->delete($rating->photo_path);
        }

        $rating->delete();

        return back()->with('success', 'Review removed successfully.');
    }
}

