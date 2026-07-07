<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->input('q')) {
            $keyword = '%'.strtolower($search).'%';
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(nama_barang) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(kode_barang) LIKE ?', [$keyword])
                  ->orWhereRaw('LOWER(lokasi_penyimpanan) LIKE ?', [$keyword]);
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('nama_barang')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->hasRole('admin', 'staff'), 403, 'Manager hanya memiliki akses melihat data.');

        $data = $this->validated($request);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'borrowingDetails.borrowing');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($request->user()->hasRole('admin', 'staff'), 403, 'Manager hanya memiliki akses melihat data.');

        $data = $this->validated($request, $product->id);

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_unless(request()->user()->hasRole('admin', 'staff'), 403, 'Manager hanya memiliki akses melihat data.');

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode_barang' => [
                'required', 'string', 'max:50',
                Rule::unique('products', 'kode_barang')->ignore($ignoreId),
            ],
            'nama_barang' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'lokasi_penyimpanan' => ['nullable', 'string', 'max:255'],
            'kondisi_barang' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);
    }
}