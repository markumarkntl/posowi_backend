<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function ringkasan(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $transaksis = Transaksi::whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date   . ' 23:59:59',
        ])->where('status', 'selesai')->get();

        $totalPendapatan = $transaksis->sum('total');
        $totalTransaksi  = $transaksis->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_pendapatan' => $totalPendapatan,
                'total_transaksi'  => $totalTransaksi,
                'rata_rata'        => $totalTransaksi > 0 ? round($totalPendapatan / $totalTransaksi, 2) : 0,
                'start_date'       => $request->start_date,
                'end_date'         => $request->end_date,
            ],
        ]);
    }

    public function transaksi(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $transaksis = Transaksi::with('items')
            ->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date   . ' 23:59:59',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $transaksis,
        ]);
    }

    public function harian(Request $request): JsonResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $transaksis = Transaksi::whereDate('created_at', $request->tanggal)
            ->where('status', 'selesai')
            ->with('items')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'tanggal'          => $request->tanggal,
                'total_pendapatan' => $transaksis->sum('total'),
                'total_transaksi'  => $transaksis->count(),
                'transaksis'       => $transaksis,
            ],
        ]);
    }

    public function bulanan(Request $request): JsonResponse
    {
        $request->validate([
            'tahun' => 'required|integer|min:2000|max:2099',
            'bulan' => 'required|integer|min:1|max:12',
        ]);

        $transaksis = Transaksi::whereYear('created_at', $request->tahun)
            ->whereMonth('created_at', $request->bulan)
            ->where('status', 'selesai')
            ->with('items')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'tahun'            => $request->tahun,
                'bulan'            => $request->bulan,
                'total_pendapatan' => $transaksis->sum('total'),
                'total_transaksi'  => $transaksis->count(),
                'transaksis'       => $transaksis,
            ],
        ]);
    }

    public function rangkuman(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_pendapatan'     => Transaksi::where('status', 'selesai')->sum('total'),
                'total_transaksi'      => Transaksi::where('status', 'selesai')->count(),
                'pendapatan_bulan_ini' => Transaksi::where('status', 'selesai')
                                            ->whereYear('created_at', now()->year)
                                            ->whereMonth('created_at', now()->month)
                                            ->sum('total'),
                'pendapatan_hari_ini'  => Transaksi::where('status', 'selesai')
                                            ->whereDate('created_at', today())
                                            ->sum('total'),
            ],
        ]);
    }
}