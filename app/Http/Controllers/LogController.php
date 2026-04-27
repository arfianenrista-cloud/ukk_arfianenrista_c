<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->search) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('NamaPelaku', 'like', "%$s%")
                  ->orWhere('Kegiatan', 'like', "%$s%")
                  ->orWhere('Keterangan', 'like', "%$s%");
            });
        }
        if ($request->role) $query->where('RolePelaku', $request->role);

        $logs = $query->paginate(25)->withQueryString();
        return view('logs.index', compact('logs'));
    }
}
