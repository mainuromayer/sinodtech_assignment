<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     *  show data
     */
    public function index()
    {
        $branchesCount = \App\Models\Branch::count();
        $productsCount = \App\Models\Product::count();
        $customersCount = \App\Models\Customer::count();
        $salesCount = \App\Models\Sale::count();
        $recentSales = \App\Models\Sale::with(['customer', 'branch'])->latest()->take(5)->get();
        
        // Calculate total stock across all branches
        $totalStock = \Illuminate\Support\Facades\DB::table('branch_product')->sum('stock_quantity') ?? 0;
        
        // Count lost customers (90 days)
        $lostCustomersCount = \App\Models\Customer::lost(90)->count();

        return view('backend.dashboard', compact(
            'branchesCount',
            'productsCount',
            'customersCount',
            'salesCount',
            'recentSales',
            'totalStock',
            'lostCustomersCount'
        ));
    }
}
