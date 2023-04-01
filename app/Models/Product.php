<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'category_id', 'color_id', 'image_avatar', 'image', 'price', 'size_id', 'quantity'
    ];

    public $appends = ['image_avatar_url'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }

    public function getImageAvatarUrlAttribute() {
        return asset('images/avatar/'.$this->image_avatar);
    }

    // public function getImageUrlAttribute() {
    //     return asset('images/images/', array($this->image));
    // }
}
