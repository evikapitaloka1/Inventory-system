<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->input('q')) {
            $query->whereRaw('LOWER(nama_barang) LIKE ?', ['%'.strtolower($search).'%']);
        }

        $products = $query->orderBy('nama_barang')->paginate(15);

        return response()->json($products);
    }

    /**
     * GET /api/products/{product}
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }
}
