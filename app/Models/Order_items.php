<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_items extends Model
{
    protected $fillable = ['order_id', 'book_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class);
    }
}
