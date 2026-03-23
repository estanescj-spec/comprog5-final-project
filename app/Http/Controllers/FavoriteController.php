<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product.images')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = Auth::user();
        $favorite = Favorite::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($favorite, 201);
        }
        return redirect()->back()->with('success', 'Added to favorites.');
    }

    public function destroy($productId)
    {
        $user = Auth::user();
        $deleted = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['deleted' => $deleted > 0]);
        }
        return redirect()->back()->with('success', 'Removed from favorites.');
    }
}
