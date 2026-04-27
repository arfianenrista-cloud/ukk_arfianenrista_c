<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->search) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('Judul', 'like', "%$s%")
                  ->orWhere('Penulis', 'like', "%$s%")
                  ->orWhere('ISBN', 'like', "%$s%")
                  ->orWhere('Barcode', 'like', "%$s%");
            });
        }
        if ($request->kategori) {
            $query->whereHas('kategori', fn($q) => $q->where('kategoribuku.KategoriID', $request->kategori));
        }
        if ($request->status) {
            $query->where('Status', $request->status);
        }

        $buku = $query->paginate(12)->withQueryString();
        $kategori = KategoriBuku::all();

        return view('buku.index', compact('buku', 'kategori'));
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'ulasan.user'])->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function create()
    {
        $this->authorizeStaff();
        $kategori = KategoriBuku::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();
        $request->validate([
            'Judul'       => 'required|string|max:255',
            'Penulis'     => 'required|string|max:255',
            'JumlahTotal' => 'required|integer|min:1',
            'kategori_ids'=> 'required|array',
        ]);

        $data = $request->except(['kategori_ids', 'cover']);
        $data['JumlahTersedia'] = $request->JumlahTotal;
        $data['Status'] = 'tersedia';

        if ($request->hasFile('cover')) {
            $data['CoverImage'] = $request->file('cover')->store('covers', 'public');
        }

        $buku = Buku::create($data);
        $buku->kategori()->sync($request->kategori_ids);

        ActivityLog::catat('TAMBAH BUKU', "Buku: {$buku->Judul} (ID: {$buku->BukuID})");

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $this->authorizeStaff();
        $buku = Buku::with('kategori')->findOrFail($id);
        $kategori = KategoriBuku::all();
        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeStaff();
        $buku = Buku::findOrFail($id);

        $request->validate([
            'Judul'       => 'required|string|max:255',
            'Penulis'     => 'required|string|max:255',
            'JumlahTotal' => 'required|integer|min:1',
        ]);

        $data = $request->except(['kategori_ids', 'cover', '_method', '_token']);

        if ($request->hasFile('cover')) {
            if ($buku->CoverImage) Storage::disk('public')->delete($buku->CoverImage);
            $data['CoverImage'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);
        if ($request->kategori_ids) $buku->kategori()->sync($request->kategori_ids);

        ActivityLog::catat('EDIT BUKU', "Buku: {$buku->Judul} (ID: {$buku->BukuID})");

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();
        $buku = Buku::findOrFail($id);
        $judul = $buku->Judul;

        if ($buku->peminjaman()->whereIn('StatusPeminjaman', ['dipinjam', 'terlambat'])->exists()) {
            return back()->withErrors(['error' => 'Buku masih dipinjam, tidak dapat dihapus!']);
        }

        if ($buku->CoverImage) Storage::disk('public')->delete($buku->CoverImage);
        $buku->delete();

        ActivityLog::catat('HAPUS BUKU', "Buku: {$judul} (ID: {$id})");

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    private function authorizeStaff()
    {
        if (!auth()->user()->isStaff()) abort(403, 'Akses ditolak');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) abort(403, 'Hanya admin yang dapat melakukan ini');
    }
}
