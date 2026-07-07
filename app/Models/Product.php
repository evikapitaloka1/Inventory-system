<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'category_id',
        'stok',
        'stok_minimum',
        'lokasi_penyimpanan',
        'kondisi_barang',
        'gambar',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'stok_minimum' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function isLowStock(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public function isAvailable(): bool
    {
        return $this->stok > 0;
    }

    public function imageUrl(): string
    {
        return $this->gambar
            ? asset('storage/'.$this->gambar)
            : 'https://ui-avatars.com/api/?background=7C3AED&color=fff&name='.urlencode($this->nama_barang);
    }
}
