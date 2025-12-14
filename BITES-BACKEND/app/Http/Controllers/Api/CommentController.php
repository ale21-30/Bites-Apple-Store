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
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:200',
        ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'comment' => $request->comment,
        ]);

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