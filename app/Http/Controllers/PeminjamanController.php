<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Peminjaman::with(['user', 'buku', 'petugas']);

        if ($user->isSiswa()) {
            $query->where('UserID', $user->UserID);
        } else {
            if ($request->search) {
                $s = $request->search;
                $query->whereHas('user', fn($q) => $q->where('NamaLengkap', 'like', "%$s%")->orWhere('NIS', 'like', "%$s%"))
                      ->orWhereHas('buku', fn($q) => $q->where('Judul', 'like', "%$s%"));
            }
            if ($request->status) $query->where('StatusPeminjaman', $request->status);
        }

        // Auto-update status terlambat
        Peminjaman::where('StatusPeminjaman', 'dipinjam')
            ->where('TanggalJatuhTempo', '<', Carbon::now())
            ->update(['StatusPeminjaman' => 'terlambat']);

        $peminjaman = $query->latest()->paginate(15)->withQueryString();
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        if (!auth()->user()->isStaff()) abort(403);
        $buku = Buku::where('Status', 'tersedia')->where('JumlahTersedia', '>', 0)->get();
        $siswa = User::where('Role', 'siswa')->where('Status', 'aktif')->get();
        return view('peminjaman.create', compact('buku', 'siswa'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isStaff()) abort(403);

        $request->validate([
            'UserID'            => 'required|exists:users,UserID',
            'BukuID'            => 'required|exists:buku,BukuID',
            'TanggalPeminjaman' => 'required|date',
            'TanggalJatuhTempo' => 'required|date|after:TanggalPeminjaman',
        ]);

        $buku = Buku::findOrFail($request->BukuID);
        if ($buku->JumlahTersedia <= 0) {
            return back()->withErrors(['BukuID' => 'Buku tidak tersedia!']);
        }

        // Cek apakah siswa sudah meminjam buku yang sama
        $existing = Peminjaman::where('UserID', $request->UserID)
            ->where('BukuID', $request->BukuID)
            ->whereIn('StatusPeminjaman', ['dipinjam', 'terlambat'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['BukuID' => 'Siswa sudah meminjam buku ini!']);
        }

        $peminjaman = Peminjaman::create([
            'UserID'            => $request->UserID,
            'BukuID'            => $request->BukuID,
            'PetugasID'         => auth()->id(),
            'TanggalPeminjaman' => $request->TanggalPeminjaman,
            'TanggalJatuhTempo' => $request->TanggalJatuhTempo,
            'StatusPeminjaman'  => 'dipinjam',
            'StatusDenda'       => 'bebas',
            'Catatan'           => $request->Catatan,
        ]);

        // Kurangi stok
        $buku->decrement('JumlahTersedia');
        if ($buku->JumlahTersedia <= 0) $buku->update(['Status' => 'dipinjam']);

        ActivityLog::catat('TRANSAKSI PEMINJAMAN', "Peminjaman ID: {$peminjaman->PeminjamanID}, Buku: {$buku->Judul}, Siswa ID: {$request->UserID}");

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function kembalikan(Request $request, $id)
    {
        if (!auth()->user()->isStaff()) abort(403);

        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        if ($peminjaman->StatusPeminjaman === 'dikembalikan') {
            return back()->with('error', 'Buku sudah dikembalikan!');
        }

        $request->validate([
            'kondisi' => 'required|in:baik,rusak,hilang',
        ]);

        $tglKembali = Carbon::now();
        $status = 'dikembalikan';
        $denda = 0;
        $statusDenda = 'bebas';

        // Hitung denda keterlambatan
        if ($tglKembali->isAfter($peminjaman->TanggalJatuhTempo)) {
            $hariTelat = $tglKembali->diffInDays($peminjaman->TanggalJatuhTempo);
            $denda += $hariTelat * 1000;
        }

        // Denda kondisi
        if ($request->kondisi === 'rusak') {
            $denda += 25000;
            $status = 'rusak';
        } elseif ($request->kondisi === 'hilang') {
            $denda += 100000;
            $status = 'hilang';
        }

        $statusDenda = $denda > 0 ? 'belum_bayar' : 'bebas';

        $peminjaman->update([
            'TanggalPengembalian' => $tglKembali,
            'StatusPeminjaman'    => $status,
            'Denda'               => $denda,
            'StatusDenda'         => $statusDenda,
            'Catatan'             => $request->Catatan,
        ]);

        // Kembalikan stok (kecuali hilang)
        if ($request->kondisi !== 'hilang') {
            $peminjaman->buku->increment('JumlahTersedia');
            if ($peminjaman->buku->JumlahTersedia > 0) {
                $peminjaman->buku->update(['Status' => 'tersedia']);
            }
            if ($request->kondisi === 'rusak') {
                $peminjaman->buku->update(['Kondisi' => 'rusak']);
            }
        } else {
            $peminjaman->buku->decrement('JumlahTotal');
        }

        ActivityLog::catat('PENGEMBALIAN BUKU', "Peminjaman ID: {$id}, Kondisi: {$request->kondisi}, Denda: Rp {$denda}");

        return redirect()->route('peminjaman.index')->with('success', "Buku berhasil dikembalikan!" . ($denda > 0 ? " Denda: Rp " . number_format($denda) : ""));
    }

    public function bayarDenda(Request $request, $id)
    {
        if (!auth()->user()->isStaff()) abort(403);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['StatusDenda' => 'sudah_bayar']);

        ActivityLog::catat('BAYAR DENDA', "Peminjaman ID: {$id}, Denda: Rp {$peminjaman->Denda}");

        return back()->with('success', 'Denda berhasil dibayar!');
    }

    // Siswa bisa request peminjaman mandiri
    public function requestPinjam(Request $request)
    {
        $user = auth()->user();
        if (!$user->isSiswa()) abort(403);

        $request->validate([
            'BukuID' => 'required|exists:buku,BukuID',
        ]);

        $buku = Buku::findOrFail($request->BukuID);
        if ($buku->JumlahTersedia <= 0) {
            return back()->withErrors(['BukuID' => 'Buku tidak tersedia!']);
        }

        return redirect()->back()->with('info', 'Silakan hubungi petugas perpustakaan dengan membawa kartu siswa untuk meminjam buku: ' . $buku->Judul);
    }
}
