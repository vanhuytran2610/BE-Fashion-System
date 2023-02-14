<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'category_id', 'color_id', 'price'
    ];

    protected $casts = [
        'size' => 'array'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    // public function size() {
    //     return $this->belongsTo(Size::class);
    // }
}
