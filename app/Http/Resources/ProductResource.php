<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Calculate total available stock across all branches
        $totalStock = $this->branches->sum('pivot.stock_quantity');

        return [
            'sku' => $this->sku,
            'product_name' => $this->name,
            'price' => (float) $this->price,
            'available_stock' => $totalStock,
            'branch_stock' => $this->branches->map(function ($branch) {
                return [
                    'branch_name' => $branch->name,
                    'stock' => $branch->pivot->stock_quantity,
                ];
            }),
        ];
    }
}
