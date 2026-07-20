<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Http\Requests\StoreSaleRequest;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'branch', 'employee', 'items.product'])->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $branches = Branch::with('products')->get();
        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();
        return view('sales.create', compact('branches', 'customers', 'employees', 'products'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $branchId = $request->input('branch_id');
            $customerId = $request->input('customer_id');
            $employeeId = $request->input('employee_id');
            $items = $request->input('items', []);

            $branch = Branch::findOrFail($branchId);
            $customer = $customerId ? Customer::find($customerId) : null;

            // 1. Calculate total amount and prepare items
            $totalAmount = 0;
            $itemsData = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $price = $product->price;
                $totalAmount += $price * $quantity;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // 2. Create Sale
            $sale = Sale::create([
                'customer_id' => $customerId,
                'branch_id' => $branchId,
                'employee_id' => $employeeId,
                'total_amount' => $totalAmount,
            ]);

            // 3. Create Sale Items & Deduct Stock
            foreach ($itemsData as $itemData) {
                $sale->items()->create($itemData);

                // Deduct stock from the branch
                $branchProduct = $branch->products()->where('product_id', $itemData['product_id'])->first();
                if ($branchProduct) {
                    $newStock = $branchProduct->pivot->stock_quantity - $itemData['quantity'];
                    $branch->products()->updateExistingPivot($itemData['product_id'], [
                        'stock_quantity' => max(0, $newStock)
                    ]);
                }
            }

            // 4. KPI Tracking: If customer was assigned to an employee and was inactive (lost)
            // Wait, we check if the customer is currently assigned to an employee.
            // If they are assigned, and this is their first purchase since being assigned (or they were lost),
            // we increase the assigned employee's KPI score.
            // Let's check if they were lost before this purchase.
            // Since this sale is already created, we can check if they had a previous purchase within 90 days.
            // Or we can check if they have an assigned employee.
            if ($customer && $customer->assigned_employee_id) {
                // Check if they were "lost" before this purchase.
                // A customer is lost if they had no purchases in the last 90 days (excluding this new sale).
                $previousSalesCount = $customer->sales()->where('id', '!=', $sale->id)->count();
                $lastPreviousSale = $customer->sales()->where('id', '!=', $sale->id)->latest()->first();

                $isLostBefore = false;
                if ($previousSalesCount === 0) {
                    $isLostBefore = $customer->created_at->diffInDays(now()) >= 90;
                } else if ($lastPreviousSale) {
                    $isLostBefore = $lastPreviousSale->created_at->diffInDays(now()) >= 90;
                }

                if ($isLostBefore) {
                    // Increase the assigned employee's KPI score by 10 points
                    $assignedEmployee = Employee::find($customer->assigned_employee_id);
                    if ($assignedEmployee) {
                        $assignedEmployee->increment('kpi_score', 10);
                    }
                }
            }

            return $sale;
        });

        // 5. Send Invoice Email (Bonus Feature)
        if ($sale->customer && $sale->customer->email) {
            try {
                Mail::to($sale->customer->email)->send(new InvoiceMail($sale));
            } catch (\Exception $e) {
                // Log error or ignore if mail server is not configured
                logger()->error('Failed to send invoice email: ' . $e->getMessage());
            }
        }

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully and invoice sent.');
    }
}
