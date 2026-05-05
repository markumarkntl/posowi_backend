<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'kategori_id');
    }
}