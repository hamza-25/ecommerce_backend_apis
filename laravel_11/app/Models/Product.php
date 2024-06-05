<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "price",
        "category_id",
    ];

    function category() {
        return $this->belongsTo(Category::class);
    }

    function orders() {
         return $this->hasMany(Order::class);
    }
}
