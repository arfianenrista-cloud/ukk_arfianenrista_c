<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'BukuID';

    protected $fillable = [
        'Judul', 'Penulis', 'Penerbit', 'TahunTerbit', 'ISBN',
        'Barcode', 'JumlahTotal', 'JumlahTersedia', 'Kondisi',
        'Status', 'Deskripsi', 'CoverImage',
    ];

    public function kategori()
    {
        return $this->belongsToMany(KategoriBuku::class, 'kategoribuku_relasi', 'BukuID', 'KategoriID', 'BukuID', 'KategoriID');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'BukuID', 'BukuID');
    }

    public function ulasan()
    {
        return $this->hasMany(UlasanBuku::class, 'BukuID', 'BukuID');
    }

    public function koleksi()
    {
        return $this->hasMany(KoleksiPribadi::class, 'BukuID', 'BukuID');
    }

    public function getRatingAttribute()
    {
        $avg = $this->ulasan()->avg('Rating');
        return $avg ? round($avg, 1) : 0;
    }
}
