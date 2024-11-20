<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smartphone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'description', 'price', 'release_year', 'sim_count', 'memory_options', 'color_options', 'category_id', 'image_url'];

    // Связь с брендом
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Связь с категорией
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Связь с характеристиками
    public function characteristics()
    {
        return $this->hasMany(SmartphoneCharacteristic::class);
    }

    // Связь с позициями в заказах
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}