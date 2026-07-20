<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ECommerceApiController extends Controller
{
    public function index()
    {
        $products = Product::with('branches')->get();
        return ProductResource::collection($products);
    }

    public function show($sku)
    {
        $product = Product::with('branches')->where('sku', $sku)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }
}
