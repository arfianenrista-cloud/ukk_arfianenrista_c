<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin() || $user->isPetugas()) {
            $data['totalBuku']      = Buku::count();
            $data['totalAnggota']   = User::where('Role', 'siswa')->count();
            $data['totalDipinjam']  = Peminjaman::whereIn('StatusPeminjaman', ['dipinjam', 'terlambat'])->count();
            $data['totalTerlambat'] = Peminjaman::where('StatusPeminjaman', 'terlambat')
                ->orWhere(function($q) {
                    $q->where('StatusPeminjaman', 'dipinjam')
                      ->where('TanggalJatuhTempo', '<', Carbon::now());
                })->count();

            // Auto-update terlambat
            Peminjaman::where('StatusPeminjaman', 'dipinjam')
                ->where('TanggalJatuhTempo', '<', Carbon::now())
                ->update(['StatusPeminjaman' => 'terlambat']);

            $data['peminjamanTerbaru'] = Peminjaman::with(['user', 'buku'])
                ->latest()->take(10)->get();

            $data['alertTerlambat'] = Peminjaman::with(['user', 'buku'])
                ->where('StatusPeminjaman', 'terlambat')
                ->where('StatusDenda', 'belum_bayar')
                ->get();

            $data['recentLogs'] = ActivityLog::latest('created_at')->take(15)->get();

            // Chart data - peminjaman per bulan
            $data['chartData'] = Peminjaman::selectRaw('MONTH(TanggalPeminjaman) as bulan, COUNT(*) as total')
                ->whereYear('TanggalPeminjaman', Carbon::now()->year)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('total', 'bulan');

        } else {
            // Siswa dashboard
            $data['peminjamanAktif'] = $user->peminjamanAktif()->with('buku')->get();
            $data['riwayatPeminjaman'] = $user->peminjaman()->with('buku')->latest()->take(5)->get();
            $data['koleksi'] = $user->koleksi()->with('buku')->take(4)->get();
            $data['bukuTersedia'] = Buku::where('Status', 'tersedia')->latest()->take(6)->get();
            $data['totalDenda'] = $user->peminjaman()
                ->where('StatusDenda', 'belum_bayar')
                ->sum('Denda');
        }

        return view('dashboard', compact('user', 'data'));
    }
}
