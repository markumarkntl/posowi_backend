<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok'  => 'integer',
        'aktif' => 'boolean',
    ];
}