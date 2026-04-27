<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $query = User::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('NamaLengkap', 'like', "%$s%")
                  ->orWhere('Username', 'like', "%$s%")
                  ->orWhere('NIS', 'like', "%$s%")
                  ->orWhere('Email', 'like', "%$s%");
            });
        }
        if ($request->role) $query->where('Role', $request->role);

        $users = $query->paginate(15)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'NamaLengkap' => 'required|string|max:255',
            'Username'    => 'required|string|max:50|unique:users,Username',
            'Email'       => 'required|email|unique:users,Email',
            'Role'        => 'required|in:admin,petugas,siswa',
            'Password'    => 'required|string|min:6',
        ]);

        $user = User::create([
            'NamaLengkap' => $request->NamaLengkap,
            'Username'    => $request->Username,
            'Email'       => $request->Email,
            'Password'    => Hash::make($request->Password),
            'Role'        => $request->Role,
            'NIS'         => $request->NIS,
            'Rayon'       => $request->Rayon,
            'Rombel'      => $request->Rombel,
            'Barcode'     => $request->NIS ? 'STD-' . $request->NIS : null,
            'Status'      => 'aktif',
        ]);

        ActivityLog::catat('TAMBAH USER', "User: {$user->NamaLengkap} ({$user->Role})");
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $user = User::findOrFail($id);

        $request->validate([
            'NamaLengkap' => 'required|string|max:255',
            'Email'       => 'required|email|unique:users,Email,' . $id . ',UserID',
            'Status'      => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->except(['Password', '_method', '_token']);
        if ($request->Password) {
            $request->validate(['Password' => 'string|min:6']);
            $data['Password'] = Hash::make($request->Password);
        }

        $user->update($data);
        ActivityLog::catat('EDIT USER', "User: {$user->NamaLengkap} (ID: {$id})");
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        if (auth()->id() == $id) return back()->withErrors(['error' => 'Tidak bisa menghapus akun sendiri!']);

        $user = User::findOrFail($id);
        $nama = $user->NamaLengkap;
        $user->delete();

        ActivityLog::catat('HAPUS USER', "User: {$nama} (ID: {$id})");
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
