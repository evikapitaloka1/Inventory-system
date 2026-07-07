<?php

namespace App\Http\Controllers;

use App\Exports\BorrowingsExport;
use App\Exports\ProductsExport;
use App\Models\Borrowing;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function productsPdf()
    {
        $products = Product::with('category')->orderBy('nama_barang')->get();

        $pdf = Pdf::loadView('reports.products-pdf', compact('products'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-inventaris-'.now()->format('Y-m-d').'.pdf');
    }

    public function productsExcel()
    {
        return Excel::download(new ProductsExport, 'laporan-inventaris-'.now()->format('Y-m-d').'.xlsx');
    }

    public function borrowingsPdf()
    {
        $borrowings = Borrowing::with(['details.product', 'user'])->orderByDesc('tanggal_pinjam')->get();

        $pdf = Pdf::loadView('reports.borrowings-pdf', compact('borrowings'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-'.now()->format('Y-m-d').'.pdf');
    }

    public function borrowingsExcel()
    {
        return Excel::download(new BorrowingsExport, 'laporan-peminjaman-'.now()->format('Y-m-d').'.xlsx');
    }
}
