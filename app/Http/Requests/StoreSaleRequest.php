<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Branch;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'nullable|exists:employees,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $branchId = $this->input('branch_id');
            $items = $this->input('items', []);

            if (!$branchId || empty($items)) {
                return;
            }

            $branch = Branch::find($branchId);
            if (!$branch) {
                return;
            }

            foreach ($items as $index => $item) {
                $productId = $item['product_id'] ?? null;
                $quantity = $item['quantity'] ?? 0;

                if (!$productId || $quantity <= 0) {
                    continue;
                }

                $branchProduct = $branch->products()->where('product_id', $productId)->first();
                $availableStock = $branchProduct ? $branchProduct->pivot->stock_quantity : 0;

                if ($availableStock < $quantity) {
                    $productName = $branchProduct ? $branchProduct->name : 'Product';
                    $validator->errors()->add(
                        "items.{$index}.quantity",
                        "Insufficient stock for {$productName} in this branch. Available: {$availableStock}."
                    );
                }
            }
        });
    }
}
