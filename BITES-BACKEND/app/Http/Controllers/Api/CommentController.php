<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Listar comentarios por producto
    public function index($productId)
    {
        return Comment::with('user:id,name')
            ->where('product_id', $productId)
            ->latest()
            ->get();
    }

    // Crear comentario (usuario autenticado)
    public function store(Request $request, $product)
    {
        $request->validate([
            'comment' => 'required|string|max:200',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product,
            'content' => $request->comment, // La columna en DB es 'content'
        ]);

        // Cargar la relación del usuario para retornarla
        $comment->load('user:id,name');

        return response()->json($comment, 201);
    }

    // Eliminar comentario (solo admin)
    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Comentario eliminado'
        ]);
    }
}