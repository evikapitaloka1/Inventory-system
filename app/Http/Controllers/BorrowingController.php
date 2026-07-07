<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['details.product', 'user']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Staff hanya melihat riwayat miliknya sendiri, admin/manager melihat semua.
        if ($request->user()->isStaff()) {
            $query->where('user_id', $request->user()->id);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $products = Product::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('borrowings.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_peminjam' => ['required', 'string', 'max:255'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'catatan' => ['nullable', 'string'],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['exists:products,id'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['integer', 'min:1'],
        ]);

        // --- Validasi stok: jumlah yang diajukan tidak boleh melebihi stok yang tersedia saat ini. ---
        $validator = Validator::make($data, []);

        // Jumlah per produk digabung dulu, jaga-jaga kalau produk yang sama dipilih di beberapa baris.
        $requestedPerProduct = [];
        foreach ($data['products'] as $index => $productId) {
            $qty = (int) ($data['quantities'][$index] ?? 0);
            $requestedPerProduct[$productId] = ($requestedPerProduct[$productId] ?? 0) + $qty;
        }

        $products = Product::whereIn('id', array_keys($requestedPerProduct))->get()->keyBy('id');

        foreach ($requestedPerProduct as $productId => $qtyRequested) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            if ($qtyRequested > $product->stok) {
                $validator->errors()->add(
                    'products',
                    "Jumlah pinjam \"{$product->nama_barang}\" ({$qtyRequested}) melebihi stok yang tersedia (stok saat ini: {$product->stok})."
                );
            }
        }

        if ($validator->errors()->isNotEmpty()) {
            return back()->withErrors($validator)->withInput();
        }
        // --- Selesai validasi stok ---

        DB::transaction(function () use ($data, $request) {
            $borrowing = Borrowing::create([
                'kode_peminjaman' => 'PJM-'.str_pad((string) (Borrowing::max('id') + 1), 4, '0', STR_PAD_LEFT),
                'nama_peminjam' => $data['nama_peminjam'],
                'user_id' => $request->user()->id,
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $data['tanggal_kembali_rencana'],
                'status' => 'pending',
                'catatan' => $data['catatan'] ?? null,
            ]);

            foreach ($data['products'] as $index => $productId) {
                $qty = $data['quantities'][$index] ?? 1;

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'product_id' => $productId,
                    'jumlah' => $qty,
                ]);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Pengajuan peminjaman berhasil dibuat, menunggu persetujuan.');
    }

    public function approve(Borrowing $borrowing, Request $request)
    {
        abort_unless($request->user()->hasRole('admin', 'manager'), 403);

        DB::transaction(function () use ($borrowing, $request) {
            foreach ($borrowing->details as $detail) {
                $product = $detail->product;
                abort_if($product->stok < $detail->jumlah, 422, "Stok {$product->nama_barang} tidak mencukupi.");
                $product->decrement('stok', $detail->jumlah);
            }

            $borrowing->update([
                'status' => 'dipinjam',
                'approved_by' => $request->user()->id,
            ]);
        });

        return back()->with('success', 'Peminjaman disetujui dan stok telah diperbarui.');
    }

    public function reject(Borrowing $borrowing, Request $request)
    {
        abort_unless($request->user()->hasRole('admin', 'manager'), 403);

        $data = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:500'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $borrowing->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $data['alasan_penolakan'],
            'approved_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function returnItem(Borrowing $borrowing, Request $request)
    {
        abort_unless($request->user()->hasRole('admin'), 403, 'Hanya admin yang dapat mengonfirmasi pengembalian barang.');

        abort_if($borrowing->status !== 'dipinjam', 422, 'Peminjaman ini tidak sedang berjalan.');

        $data = $request->validate([
            'foto_pengembalian' => ['nullable', 'image', 'max:2048'],
            'catatan_pengembalian' => ['nullable', 'string', 'max:500'],
            'kondisi_saat_kembali' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        DB::transaction(function () use ($borrowing, $data, $request) {
            foreach ($borrowing->details as $detail) {
                $detail->product->increment('stok', $detail->jumlah);
                $detail->update(['kondisi_saat_kembali' => $data['kondisi_saat_kembali']]);
            }

            $fotoPath = null;
            if ($request->hasFile('foto_pengembalian')) {
                $fotoPath = $request->file('foto_pengembalian')->store('returns', 'public');
            }

            $borrowing->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'foto_pengembalian' => $fotoPath,
                'catatan_pengembalian' => $data['catatan_pengembalian'] ?? null,
            ]);
        });

        return back()->with('success', 'Barang berhasil dikembalikan dan stok diperbarui.');
    }
}