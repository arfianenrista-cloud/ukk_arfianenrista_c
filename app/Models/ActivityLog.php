<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    public $timestamps = false;
    protected $fillable = ['UserID', 'NamaPelaku', 'RolePelaku', 'Kegiatan', 'Keterangan', 'IPAddress'];

    protected $casts = ['created_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    public static function catat(string $kegiatan, string $keterangan = '', $user = null): void
    {
        $u = $user ?? auth()->user();
        self::create([
            'UserID' => $u?->UserID,
            'NamaPelaku' => $u ? "{$u->NamaLengkap} ({$u->Username})" : 'System',
            'RolePelaku' => $u?->Role ?? 'system',
            'Kegiatan' => $kegiatan,
            'Keterangan' => $keterangan,
            'IPAddress' => request()->ip(),
        ]);
    }
}
