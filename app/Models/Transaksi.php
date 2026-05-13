<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'user_id',
        'pelanggan_id',  // ← tambah
        'pelanggan',
        'subtotal',
        'pajak',
        'total',
        'metode_pembayaran',
        'status',
    ];

    // FIX: ganti 'decimal:2' → 'float' supaya Laravel kirim angka bukan String
    protected $casts = [
        'subtotal'     => 'float',
        'pajak'        => 'float',
        'total'        => 'float',
        'pelanggan_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // tambah relasi pelanggan
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id');
    }
}