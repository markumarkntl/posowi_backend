<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(): JsonResponse
    {
        $transaksis = Transaksi::with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $transaksis,
        ]);
    }

    public function show(Transaksi $transaksi): JsonResponse
    {
        $transaksi->load('items', 'user');

        return response()->json([
            'success' => true,
            'data'    => $transaksi,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.produk_id' => 'required|integer|exists:products,id',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.harga'     => 'required|numeric|min:0',
            'metode_pembayaran' => 'nullable|string|max:50',
            'pelanggan'         => 'nullable|string|max:255',
            'pajak'             => 'nullable|numeric|min:0',
            'total'             => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal  = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $produk = Product::findOrFail($item['produk_id']);

                if ($produk->stok < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk \"{$produk->nama}\" tidak mencukupi. Tersisa {$produk->stok}.",
                    ], 422);
                }

                $itemSubtotal = $item['harga'] * $item['qty'];
                $subtotal    += $itemSubtotal;

                $itemsData[] = [
                    'produk_id'   => $produk->id,
                    'nama_produk' => $produk->nama,
                    'harga'       => $item['harga'],
                    'qty'         => $item['qty'],
                    'subtotal'    => $itemSubtotal,
                ];

                $produk->decrement('stok', $item['qty']);
            }

            $transaksi = Transaksi::create([
                'user_id'           => $request->user()->id,
                'pelanggan'         => $validated['pelanggan'] ?? null,
                'subtotal'          => $subtotal,
                'pajak'             => $validated['pajak'] ?? 0,
                'total'             => $validated['total'],
                'metode_pembayaran' => $validated['metode_pembayaran'] ?? 'tunai',
                'status'            => 'selesai',
            ]);

            $transaksi->items()->createMany($itemsData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data'    => $transaksi->load('items'),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }
}