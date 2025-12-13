<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        return response()->json(
            Product::with('category')->get(),
            200
        );
    }

    // POST /api/products (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'type'        => 'required|in:equipo,accesorio',
            'color'       => 'nullable|string|max:255',
            'storage'     => 'nullable|string|max:255',
            'image_url'   => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }
}
