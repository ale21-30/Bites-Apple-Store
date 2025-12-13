<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Listar favoritos del usuario autenticado
    public function index(Request $request)
    {
        return $request->user()
            ->favorites()
            ->with('product')
            ->get();
    }

    // Agregar a favoritos
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'message' => 'Producto agregado a favoritos',
            'favorite' => $favorite,
        ], 201);
    }

    // Eliminar de favoritos
    public function destroy($id)
    {
        Favorite::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'message' => 'Producto eliminado de favoritos'
        ]);
    }
}