<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori_id',
        'aktif',
    ];

    protected $casts = [
        'harga'       => 'decimal:2',
        'stok'        => 'integer',
        'aktif'       => 'boolean',
        'kategori_id' => 'integer',
    ];

    // Append 'kategori' sebagai string agar frontend tetap bisa baca p['kategori']
    protected $appends = ['kategori'];

    public function kategoriRelasi(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function getKategoriAttribute(): ?string
    {
        return $this->kategoriRelasi?->nama;
    }
}