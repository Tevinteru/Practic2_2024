<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Связь с коллекцией смартфонов
    public function smartphones()
    {
        return $this->hasMany(Smartphone::class);
    }
}