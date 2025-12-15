<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
        'color',
        'storage',
        'image_url',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}