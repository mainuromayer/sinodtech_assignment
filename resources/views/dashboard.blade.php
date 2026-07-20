@extends('layouts.app')

@section('header_title', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-sm text-slate-400 font-medium">Total Branches</span>
                <h3 class="text-3xl font-bold mt-1 text-blue-500">{{ $branchesCount }}</h3>
            </div>
            <div class="p-3 bg-blue-500/10 text-blue-500 rounded-lg">
                <i class="fa-solid fa-store text-2xl"></i>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-sm text-slate-400 font-medium">Total Products</span>
                <h3 class="text-3xl font-bold mt-1 text-emerald-500">{{ $productsCount }}</h3>
            </div>
            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-lg">
                <i class="fa-solid fa-boxes-stacked text-2xl"></i>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-sm text-slate-400 font-medium">Total Stock (All Branches)</span>
                <h3 class="text-3xl font-bold mt-1 text-amber-500">{{ $totalStock }}</h3>
            </div>
            <div class="p-3 bg-amber-500/10 text-amber-500 rounded-lg">
                <i class="fa-solid fa-warehouse text-2xl"></i>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 flex items-center justify-between shadow-lg">
            <div>
                <span class="text-sm text-slate-400 font-medium">Lost Customers (90 Days)</span>
                <h3 class="text-3xl font-bold mt-1 text-rose-500">{{ $lostCustomersCount }}</h3>
            </div>
            <div class="p-3 bg-rose-500/10 text-rose-500 rounded-lg">
                <i class="fa-solid fa-user-slash text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Sales -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg lg:col-span-2">
            <h3 class="text-lg font-semibold mb-4 text-slate-200"><i
                    class="fa-solid fa-clock-rotate-left mr-2 text-blue-500"></i>Recent Sales</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-700/50 text-slate-200 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Sale ID</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Branch</th>
                            <th class="px-4 py-3">Total Amount</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="px-4 py-3 font-semibold text-slate-100">#{{ $sale->id }}</td>
                                <td class="px-4 py-3">{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</td>
                                <td class="px-4 py-3">{{ $sale->branch->name }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-400">
                                    ৳{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-slate-400">{{ $sale->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-slate-500">No sales recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-slate-200"><i class="fa-solid fa-bolt mr-2 text-amber-500"></i>Quick
                Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('sales.create') }}"
                    class="flex items-center justify-between p-4 bg-slate-700/50 hover:bg-slate-700 border border-slate-600 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fa-solid fa-cart-plus text-blue-500 mr-3 text-lg"></i>
                        <span class="font-medium text-slate-200">Record a New Sale</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-500 group-hover:translate-x-1 transition"></i>
                </a>
                <a href="{{ route('products.index') }}"
                    class="flex items-center justify-between p-4 bg-slate-700/50 hover:bg-slate-700 border border-slate-600 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fa-solid fa-boxes-stacked text-emerald-500 mr-3 text-lg"></i>
                        <span class="font-medium text-slate-200">Manage Products & Stock</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-500 group-hover:translate-x-1 transition"></i>
                </a>
                <a href="{{ route('customers.index') }}"
                    class="flex items-center justify-between p-4 bg-slate-700/50 hover:bg-slate-700 border border-slate-600 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fa-solid fa-users text-amber-500 mr-3 text-lg"></i>
                        <span class="font-medium text-slate-200">CRM & Lost Customers</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-500 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
        </div>
    </div>
@endsection