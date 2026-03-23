<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'shipping_address',
    ];

    /**
     * 3NF: total is derived from order_items, not stored.
     */
    public function getTotalAmountAttribute(): float
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }
        return (float) $this->items->sum(fn($item) => $item->unit_price * $item->quantity);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
