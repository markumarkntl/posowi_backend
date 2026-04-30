<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['nama' => 'Kopi Arabika', 'harga' => 25000, 'stok' => 100, 'kategori' => 'Minuman'],
            ['nama' => 'Teh Hijau',    'harga' => 15000, 'stok' => 150, 'kategori' => 'Minuman'],
            ['nama' => 'Roti Gandum',  'harga' => 20000, 'stok' => 50,  'kategori' => 'Makanan'],
            ['nama' => 'Croissant',    'harga' => 30000, 'stok' => 40,  'kategori' => 'Makanan'],
            ['nama' => 'Air Mineral',  'harga' => 8000,  'stok' => 200, 'kategori' => 'Minuman'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('✅ ' . count($products) . ' produk berhasil di-seed.');
    }
}