<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'district',
        'city',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'note'
    ];

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
