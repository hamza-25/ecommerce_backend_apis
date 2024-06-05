<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        "full_name",
        "city",
        "street",
        "province",
        "country",
        "phone",
        "zip_code",
        "user_id"
    ];

    function user() {
        return $this->belongsTo(User::class);
    }
}
