<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'author', 'publisher', 'isbn',
        'price', 'stock', 'cover_image', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }


    public function orderItems()
    {
        return $this->hasMany(Order_items::class);
    }

    public function carts()
    {
        return $this->hasMany(Carts::class);
    }
}
