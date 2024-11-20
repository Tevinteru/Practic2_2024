<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartphoneCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = ['smartphone_id', 'characteristic', 'value'];

    // Связь с смартфоном
    public function smartphone()
    {
        return $this->belongsTo(Smartphone::class);
    }
}