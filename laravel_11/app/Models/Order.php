<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "total_price",
        "transaction_id",
        "product_id",
        "user_id",
        "address_id"
    ];

    function user() {
       return $this->belongsTo(User::class);
    }

    function product() {
        return $this->belongsTo(Product::class);
     }
}