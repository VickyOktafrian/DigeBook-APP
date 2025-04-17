<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = ['order_id', 'method', 'paid_at', 'status'];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
