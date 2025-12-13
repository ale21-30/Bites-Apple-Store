<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

protected $fillable = [
    'name',
    'description',
    'price',
    'type',
    'color',
    'storage',
    'image_url',
];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}