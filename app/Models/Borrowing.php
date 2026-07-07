<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'kode_peminjaman',
        'nama_peminjam',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali',
        'status',
        'catatan',
        'alasan_penolakan',
        'foto_pengembalian',
        'catatan_pengembalian',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'dipinjam' => 'Sedang Dipinjam',
            'dikembalikan' => 'Sudah Dikembalikan',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'text-bg-warning',
            'disetujui' => 'text-bg-info',
            'dipinjam' => 'text-bg-primary',
            'dikembalikan' => 'text-bg-success',
            'ditolak' => 'text-bg-danger',
            default => 'text-bg-secondary',
        };
    }

    public function fotoPengembalianUrl(): ?string
    {
        return $this->foto_pengembalian ? asset('storage/'.$this->foto_pengembalian) : null;
    }
}