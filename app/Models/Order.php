<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'delivery_date',
        'delivery_address',
        'special_instructions',
        'confirmed_at',
        'delivered_at',
        'closed_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_date' => 'date',
        'confirmed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function canBeConfirmed()
    {
        return $this->status === 'pending';
    }

    public function canBeDelivered()
    {
        return $this->status === 'confirmed' || $this->status === 'preparing';
    }

    public function canBeClosed()
    {
        return $this->status === 'delivered' && is_null($this->closed_at);
    }

    public function canBeEdited()
    {
        return $this->status === 'confirmed' && is_null($this->delivered_at);
    }
}
