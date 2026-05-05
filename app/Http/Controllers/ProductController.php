<?php

namespace App\Http\Controllers;

use App\Events\ProductUpdated;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with('kategoriRelasi')
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'nullable|integer|exists:kategoris,id',
        ]);

        $validated['aktif'] = true;

        $product = Product::create($validated);

        event(new ProductUpdated($product, 'created'));

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product->load('kategoriRelasi'),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $product->load('kategoriRelasi'),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'nama'        => 'sometimes|string|max:255',
            'harga'       => 'sometimes|numeric|min:0',
            'stok'        => 'sometimes|integer|min:0',
            'kategori_id' => 'nullable|integer|exists:kategoris,id',
            'aktif'       => 'sometimes|boolean',
        ]);

        $product->update($validated);

        event(new ProductUpdated($product, 'updated'));

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product->load('kategoriRelasi'),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->update(['aktif' => false]);

        event(new ProductUpdated($product, 'deleted'));

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}