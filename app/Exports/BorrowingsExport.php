<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Borrowing::with(['details.product', 'user'])->orderByDesc('tanggal_pinjam')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Peminjaman',
            'Nama Peminjam',
            'Barang Dipinjam',
            'Tanggal Pinjam',
            'Rencana Kembali',
            'Tanggal Kembali',
            'Status',
        ];
    }

    public function map($borrowing): array
    {
        return [
            $borrowing->kode_peminjaman,
            $borrowing->nama_peminjam,
            $borrowing->details->map(fn ($d) => $d->product->nama_barang.' x'.$d->jumlah)->join(', '),
            optional($borrowing->tanggal_pinjam)->format('Y-m-d'),
            optional($borrowing->tanggal_kembali_rencana)->format('Y-m-d'),
            optional($borrowing->tanggal_kembali)->format('Y-m-d') ?? '-',
            $borrowing->statusLabel(),
        ];
    }
}
