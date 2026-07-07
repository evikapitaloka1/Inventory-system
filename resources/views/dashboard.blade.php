@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@if ($pendingApprovals > 0 && auth()->user()->hasRole('admin', 'manager'))
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <div><i class="bi bi-hourglass-split me-2"></i>Ada <strong>{{ $pendingApprovals }}</strong> pengajuan peminjaman menunggu persetujuan.</div>
        <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning">Lihat</a>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(124,58,237,.12); color:#7C3AED;"><i class="bi bi-box2-fill"></i></div>
            <div class="fs-3 fw-bold">{{ number_format($totalBarang) }}</div>
            <div class="text-secondary small">Total Barang (unit)</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(234,88,12,.12); color:#EA580C;"><i class="bi bi-arrow-left-right"></i></div>
            <div class="fs-3 fw-bold">{{ number_format($barangDipinjam) }}</div>
            <div class="text-secondary small">Barang Dipinjam</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(34,197,94,.12); color:#22C55E;"><i class="bi bi-check2-circle"></i></div>
            <div class="fs-3 fw-bold">{{ number_format($barangTersedia) }}</div>
            <div class="text-secondary small">Barang Tersedia</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(6,182,212,.12); color:#06B6D4;"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div class="fs-3 fw-bold">{{ $totalJenisBarang }}</div>
            <div class="text-secondary small">Jenis Barang</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-3 h-100">
            <h6 class="mb-3">Grafik Peminjaman per Bulan</h6>
            <canvas id="borrowChart" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3 h-100">
            <h6 class="mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Stok Menipis</h6>
            @forelse ($lowStockProducts as $product)
                <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div>
                        <div class="fw-semibold small">{{ $product->nama_barang }}</div>
                        <div class="text-secondary" style="font-size:.75rem;">{{ $product->kode_barang }}</div>
                    </div>
                    <span class="badge text-bg-danger">{{ $product->stok }}</span>
                </div>
            @empty
                <p class="text-secondary small mb-0">Semua stok dalam batas aman.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="card p-3 mt-3">
    <h6 class="mb-3">Peminjaman Terbaru</h6>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentBorrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->kode_peminjaman }}</td>
                        <td>{{ $borrowing->nama_peminjam }}</td>
                        <td>{{ $borrowing->details->pluck('product.nama_barang')->join(', ') }}</td>
                        <td>{{ $borrowing->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                        <td><span class="badge {{ $borrowing->statusBadgeClass() }}">{{ $borrowing->statusLabel() }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-secondary py-3">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('borrowChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: @json($chartData),
                borderColor: '#7C3AED',
                backgroundColor: 'rgba(124,58,237,.15)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#06B6D4',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endsection
