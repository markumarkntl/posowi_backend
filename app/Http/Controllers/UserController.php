<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select('id', 'nama', 'name', 'email', 'role', 'is_aktif', 'created_at')
            ->orderBy('nama')
            ->get()
            ->map(fn($u) => array_merge($u->toArray(), ['nama' => $u->nama ?? $u->name]));

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'name'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'is_aktif' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan',
            'data'    => [
                'id'       => $user->id,
                'nama'     => $user->nama,
                'email'    => $user->email,
                'role'     => $user->role,
                'is_aktif' => $user->is_aktif,
            ],
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'id'       => $user->id,
                'nama'     => $user->nama ?? $user->name,
                'email'    => $user->email,
                'role'     => $user->role,
                'is_aktif' => $user->is_aktif,
            ],
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'nama'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'sometimes|string|min:6',
            'role'     => ['sometimes', Rule::in(['admin', 'kasir'])],
            'is_aktif' => 'sometimes|boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        if (isset($validated['nama'])) {
            $validated['name'] = $validated['nama'];
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'data'    => [
                'id'       => $user->id,
                'nama'     => $user->nama ?? $user->name,
                'email'    => $user->email,
                'role'     => $user->role,
                'is_aktif' => $user->is_aktif,
            ],
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->update(['is_aktif' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus',
        ]);
    }
}