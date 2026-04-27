<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'PeminjamanID';

    protected $fillable = [
        'UserID', 'BukuID', 'PetugasID', 'TanggalPeminjaman',
        'TanggalJatuhTempo', 'TanggalPengembalian', 'StatusPeminjaman',
        'Denda', 'StatusDenda', 'Catatan',
    ];

    protected $casts = [
        'TanggalPeminjaman' => 'date',
        'TanggalJatuhTempo' => 'date',
        'TanggalPengembalian' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'BukuID', 'BukuID');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'PetugasID', 'UserID');
    }

    public function isTerlambat(): bool
    {
        if ($this->StatusPeminjaman === 'dikembalikan') return false;
        return Carbon::now()->isAfter($this->TanggalJatuhTempo);
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->isTerlambat()) return 0;
        return Carbon::now()->diffInDays($this->TanggalJatuhTempo);
    }

    public function hitungDenda(): float
    {
        $hari = $this->hari_terlambat;
        if ($hari <= 0) return 0;
        return $hari * 1000; // Rp 1.000 per hari
    }
}
