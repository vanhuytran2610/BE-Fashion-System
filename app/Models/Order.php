<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'phone',
        'address',
        'district',
        'province',
        'ward',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'note'
    ];

    // protected $with = ['order_item'];

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'user_id', 'id');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function province() {
        return $this->belongsTo(Province::class, 'province_code','code');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_code','code');
    }

    public function ward() {
        return $this->belongsTo(Ward::class, 'ward_code','code');
    }
}
