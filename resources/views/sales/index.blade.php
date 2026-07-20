@extends('backend.app')

@section('title', 'Sales History')

@section('content')
    <!--begin::Content-->
    <div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="toolbar" id="kt_toolbar">
            <div class=" container-fluid  d-flex flex-stack flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                    <!--begin::Title-->
                    <h1 class="text-dark fw-bold my-1 fs-2">
                        Sales History
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            Sales History
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Post-->
        <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div class=" container-xxl ">
                <div class="card card-flush shadow-sm">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h3 class="card-title fw-bold text-dark"><i class="fa-solid fa-receipt text-primary me-2"></i>All Sales Transactions</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-cart-plus me-2"></i>Record New Sale
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="fa-solid fa-circle-check fs-2x text-success me-4"></i>
                                <div class="d-flex flex-column">
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-80px">Sale ID</th>
                                        <th class="min-w-100px">Branch</th>
                                        <th class="min-w-150px">Customer</th>
                                        <th class="min-w-100px">Assisted By</th>
                                        <th class="min-w-200px">Items Sold</th>
                                        <th class="min-w-100px">Total Amount</th>
                                        <th class="min-w-150px">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse($sales as $sale)
                                        <tr>
                                            <td class="text-gray-800 fw-bold">#{{ $sale->id }}</td>
                                            <td>{{ $sale->branch->name }}</td>
                                            <td>
                                                @if($sale->customer)
                                                    <div class="text-gray-800 fw-bold">{{ $sale->customer->name }}</div>
                                                    <div class="text-muted fs-7">{{ $sale->customer->email }}</div>
                                                @else
                                                    <span class="text-muted italic">Walk-in Customer</span>
                                                @endif
                                            </td>
                                            <td>{{ $sale->employee ? $sale->employee->name : 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach($sale->items as $item)
                                                        <div class="fs-7 text-gray-500">
                                                            {{ $item->product->name }} (x{{ $item->quantity }}) @ ৳{{ number_format($item->price, 2) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-success fw-bold">৳{{ number_format($sale->total_amount, 2) }}</td>
                                            <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">No sales transactions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
@endsection