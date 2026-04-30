<?php

namespace App\Http\Controllers;

use App\Events\ProductUpdated;   // ✅ Tambahan import
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::where('aktif', true)
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
            'nama'      => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
            'kategori'  => 'nullable|string|max:100',
        ]);

        $product = Product::create($validated);

        event(new ProductUpdated($product, 'created'));   // ✅ Tambahan

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product,
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'nama'      => 'sometimes|string|max:255',
            'harga'     => 'sometimes|numeric|min:0',
            'stok'      => 'sometimes|integer|min:0',
            'kategori'  => 'nullable|string|max:100',
        ]);

        $product->update($validated);

        event(new ProductUpdated($product, 'updated'));   // ✅ Tambahan

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->update(['aktif' => false]);

        event(new ProductUpdated($product, 'deleted'));   // ✅ Tambahan

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}