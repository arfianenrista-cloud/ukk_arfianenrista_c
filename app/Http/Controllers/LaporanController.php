<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()?->isStaff()) abort(403);
    }

    public function index(Request $request)
    {
        $tipe = $request->get('tipe', 'peminjaman');
        $dari = $request->get('dari', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', Carbon::now()->format('Y-m-d'));

        $data = [];

        switch ($tipe) {
            case 'peminjaman':
                $data = Peminjaman::with(['user', 'buku', 'petugas'])
                    ->whereBetween('TanggalPeminjaman', [$dari, $sampai])
                    ->get();
                break;

            case 'pengembalian':
                $data = Peminjaman::with(['user', 'buku'])
                    ->where('StatusPeminjaman', 'dikembalikan')
                    ->whereBetween('TanggalPengembalian', [$dari, $sampai])
                    ->get();
                break;

            case 'terlambat':
                $data = Peminjaman::with(['user', 'buku'])
                    ->where('StatusPeminjaman', 'terlambat')
                    ->get();
                break;

            case 'hilang':
                $data = Peminjaman::with(['user', 'buku'])
                    ->where('StatusPeminjaman', 'hilang')
                    ->get();
                break;

            case 'rusak':
                $data = Peminjaman::with(['user', 'buku'])
                    ->where('StatusPeminjaman', 'rusak')
                    ->get();
                break;

            case 'denda':
                $data = Peminjaman::with(['user', 'buku'])
                    ->where('Denda', '>', 0)
                    ->whereBetween('TanggalPeminjaman', [$dari, $sampai])
                    ->get();
                break;

            case 'stok':
                $data = Buku::with('kategori')->get();
                break;
        }

        $stats = [
            'totalPeminjaman'  => Peminjaman::whereBetween('TanggalPeminjaman', [$dari, $sampai])->count(),
            'totalKembali'     => Peminjaman::where('StatusPeminjaman', 'dikembalikan')->whereBetween('TanggalPengembalian', [$dari, $sampai])->count(),
            'totalTerlambat'   => Peminjaman::where('StatusPeminjaman', 'terlambat')->count(),
            'totalDenda'       => Peminjaman::where('Denda', '>', 0)->sum('Denda'),
            'totalDendaBelum'  => Peminjaman::where('StatusDenda', 'belum_bayar')->sum('Denda'),
        ];

        ActivityLog::catat('GENERATE LAPORAN', "Tipe: {$tipe}, Dari: {$dari} s/d {$sampai}");

        return view('laporan.index', compact('data', 'tipe', 'dari', 'sampai', 'stats'));
    }
}
