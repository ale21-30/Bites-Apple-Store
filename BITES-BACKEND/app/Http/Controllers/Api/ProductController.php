<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\FirebaseService;

class ProductController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    // GET /api/products
    public function index()
    {
        return response()->json(
            Product::with('category')->get(),
            200
        );
    }

public function show(Product $product)
{
    return response()->json(
        $product->load('category')
    );
}


    // POST /api/products (admin)
public function store(Request $request, FirebaseService $firebase)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'type' => 'required|in:equipo,accesorio',
        'color' => 'nullable|string|max:100',
        'storage' => 'nullable|string|max:100',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
    ]);

    // Debug: Log si hay archivo
    \Log::info('Has file image: ' . ($request->hasFile('image') ? 'YES' : 'NO'));
    \Log::info('All files: ' . json_encode(array_keys($request->allFiles())));
    \Log::info('All inputs: ' . json_encode(array_keys($request->all())));

    if ($request->hasFile('image')) {
        try {
            \Log::info('Attempting to upload image...');
            $data['image_url'] = $firebase->uploadImage(
                $request->file('image'),
                'products'
            );
            \Log::info('Image uploaded successfully: ' . $data['image_url']);
        } catch (\Exception $e) {
            \Log::error('Error uploading image to Firebase: ' . $e->getMessage());
            return response()->json(['error' => 'Error al subir la imagen: ' . $e->getMessage()], 500);
        }
    } else {
        \Log::info('No image file found in request');
        $data['image_url'] = null;
    }

    $product = Product::create($data);

    return response()->json($product, 201);
}

public function update(Request $request, $id, FirebaseService $firebase)
{
    $product = Product::findOrFail($id);

    $data = $request->validate([
        'name' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'price' => 'sometimes|numeric',
        'type' => 'sometimes|in:equipo,accesorio',
        'color' => 'nullable|string|max:100',
        'storage' => 'nullable|string|max:100',
        'category_id' => 'sometimes|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $data['image_url'] = $firebase->uploadImage(
            $request->file('image'),
            'products'
        );
    }

    $product->update($data);

    return response()->json($product);
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return response()->json([
        'message' => 'Producto eliminado correctamente'
    ]);
}

}