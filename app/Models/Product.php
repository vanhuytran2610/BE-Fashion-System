<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\SearchTrait;

class Product extends Model
{
    use HasFactory, SearchTrait;

    protected $fillable = [
        'name', 'description', 'category_id', 'color_id', 'image_avatar', 'price'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function sizes() {
        return $this->belongsToMany(Size::class, 'product_sizes')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // public function setSizeAttribute($value) {
    //     $this->attributes['size'] = json_encode($value);
    // }

    // public function getSizeAttribute($value) {
    //     return $this->attributes['size'] = json_decode($value);
    // }

    // public function getImageAvatarUrlAttribute() {
    //     return asset('images/avatar/'.$this->image_avatar);
    // }

    // public function getImageUrlAttribute() {
    //     return asset('images/images/', array($this->image));
    // }
}
