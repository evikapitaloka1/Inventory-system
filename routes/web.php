<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome', [
        'totalBarang' => \App\Models\Product::sum('stok'),
        'totalJenis' => \App\Models\Product::count(),
        'totalKategori' => \App\Models\Category::count(),
        'featuredProducts' => \App\Models\Product::with('category')->where('stok', '>', 0)->latest()->limit(6)->get(),
    ]);
})->name('landing');

Route::post('/theme-toggle', function (\Illuminate\Http\Request $request) {
    $theme = $request->input('theme', 'light') === 'dark' ? 'dark' : 'light';

    return back()->withCookie(cookie('theme', $theme, 60 * 24 * 365));
})->name('theme.toggle');

/*
|--------------------------------------------------------------------------
| Autentikasi (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Area Terautentikasi (Dashboard & CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data Barang: admin & staff bisa kelola, manager hanya lihat (route model tetap sama, tombol disembunyikan di view)
    Route::resource('products', ProductController::class);

    // Kategori
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Peminjaman
    Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])
        ->middleware('role:admin,manager')->name('borrowings.approve');
    Route::post('borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])
        ->middleware('role:admin,manager')->name('borrowings.reject');
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'returnItem'])
    ->middleware('role:admin')->name('borrowings.return');

    // Laporan / Export (bonus fitur)
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/products/pdf', [ReportController::class, 'productsPdf'])->name('reports.products.pdf');
    Route::get('reports/products/excel', [ReportController::class, 'productsExcel'])->name('reports.products.excel');
    Route::get('reports/borrowings/pdf', [ReportController::class, 'borrowingsPdf'])->name('reports.borrowings.pdf');
    Route::get('reports/borrowings/excel', [ReportController::class, 'borrowingsExcel'])->name('reports.borrowings.excel');
});
