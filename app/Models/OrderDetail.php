<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'magazine_id',
        'quantity',
        'total_price',
    ];

    /**
     * Relationship to Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship to Magazine
     */
    public function magazine()
    {
        return $this->belongsTo(Magazine::class);
    }
}
