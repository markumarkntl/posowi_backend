<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(): JsonResponse
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'data'    => $pelanggans,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'alamat'  => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil ditambahkan',
            'data'    => $pelanggan,
        ], 201);
    }

    public function show(Pelanggan $pelanggan): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $pelanggan,
        ]);
    }

    public function update(Request $request, Pelanggan $pelanggan): JsonResponse
    {
        $validated = $request->validate([
            'nama'    => 'sometimes|required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'alamat'  => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil diupdate',
            'data'    => $pelanggan,
        ]);
    }

    public function destroy(Pelanggan $pelanggan): JsonResponse
    {
        $pelanggan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil dihapus',
        ]);
    }
}