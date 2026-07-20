<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Branch;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('branches')->get();
        $branches = Branch::all();
        return view('products.index', compact('products', 'branches'));
    }

    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::create($request->only(['name', 'sku', 'price']));

            $branchStock = $request->input('branch_stock', []);
            foreach ($branchStock as $branchId => $stock) {
                if ($stock !== null && $stock !== '') {
                    $product->branches()->attach($branchId, ['stock_quantity' => $stock]);
                }
            }
        });

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($request->only(['name', 'sku', 'price']));

            $branchStock = $request->input('branch_stock', []);
            $syncData = [];
            foreach ($branchStock as $branchId => $stock) {
                if ($stock !== null && $stock !== '') {
                    $syncData[$branchId] = ['stock_quantity' => $stock];
                }
            }
            $product->branches()->sync($syncData);
        });

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
