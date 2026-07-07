<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Product::sum('stok');
        $totalJenisBarang = Product::count();
        $barangDipinjam = DB::table('borrowing_details')
            ->join('borrowings', 'borrowings.id', '=', 'borrowing_details.borrowing_id')
            ->where('borrowings.status', 'dipinjam')
            ->sum('borrowing_details.jumlah');
        $barangTersedia = $totalBarang - $barangDipinjam;

        $lowStockProducts = Product::whereColumn('stok', '<=', 'stok_minimum')->orderBy('stok')->limit(6)->get();

        $pendingApprovals = Borrowing::where('status', 'pending')->count();

        // Grafik peminjaman per bulan (12 bulan terakhir)
        $monthlyRaw = Borrowing::selectRaw("to_char(tanggal_pinjam, 'YYYY-MM') as bulan, count(*) as total")
            ->where('tanggal_pinjam', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $period = now()->subMonths($i);
            $key = $period->format('Y-m');
            $chartLabels[] = $period->translatedFormat('M Y');
            $chartData[] = $monthlyRaw[$key] ?? 0;
        }

        $recentBorrowings = Borrowing::with(['details.product', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'totalJenisBarang',
            'barangDipinjam',
            'barangTersedia',
            'lowStockProducts',
            'pendingApprovals',
            'chartLabels',
            'chartData',
            'recentBorrowings'
        ));
    }
}
