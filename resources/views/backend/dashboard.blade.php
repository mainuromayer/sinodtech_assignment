@extends('backend.app')

@section('title', 'Admin Dashboard')

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
                        Dashboard </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted text-hover-primary">Home </a>
                        </li>

                        <li class="breadcrumb-item text-dark"></li>

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
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--Card: Branches -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center p-9">
                                <div>
                                    <div class="fs-4 fw-semibold text-gray-400">Total Branches</div>
                                    <div class="fs-2hx fw-bold text-dark">{{ $branchesCount }}</div>
                                </div>
                                <div class="symbol symbol-50px bg-light-primary">
                                    <span class="symbol-label">
                                        <i class="fa-solid fa-store fs-2x text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Card: Products -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center p-9">
                                <div>
                                    <div class="fs-4 fw-semibold text-gray-400">Total Products</div>
                                    <div class="fs-2hx fw-bold text-dark">{{ $productsCount }}</div>
                                </div>
                                <div class="symbol symbol-50px bg-light-success">
                                    <span class="symbol-label">
                                        <i class="fa-solid fa-boxes-stacked fs-2x text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Card: Total Stock -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center p-9">
                                <div>
                                    <div class="fs-4 fw-semibold text-gray-400">Total Stock</div>
                                    <div class="fs-2hx fw-bold text-dark">{{ $totalStock }}</div>
                                </div>
                                <div class="symbol symbol-50px bg-light-warning">
                                    <span class="symbol-label">
                                        <i class="fa-solid fa-warehouse fs-2x text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Card: Lost Customers -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center p-9">
                                <div>
                                    <div class="fs-4 fw-semibold text-gray-400">Lost Customers</div>
                                    <div class="fs-2hx fw-bold text-dark">{{ $lostCustomersCount }}</div>
                                </div>
                                <div class="symbol symbol-50px bg-light-danger">
                                    <span class="symbol-label">
                                        <i class="fa-solid fa-user-slash fs-2x text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-10">
                    <!-- Recent Sales Table -->
                    <div class="col-lg-8">
                        <div class="card card-flush h-100">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <h2 class="fw-bold">Recent Sales</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-80px">Sale ID</th>
                                                <th class="min-w-150px">Customer</th>
                                                <th class="min-w-100px">Branch</th>
                                                <th class="min-w-100px">Total Amount</th>
                                                <th class="min-w-100px">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @forelse($recentSales as $sale)
                                                <tr>
                                                    <td>#{{ $sale->id }}</td>
                                                    <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</td>
                                                    <td>{{ $sale->branch->name }}</td>
                                                    <td class="text-success">৳{{ number_format($sale->total_amount, 2) }}</td>
                                                    <td>{{ $sale->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No sales recorded yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-lg-4">
                        <div class="card card-flush h-100">
                            <div class="card-header align-items-center py-5">
                                <div class="card-title">
                                    <h2 class="fw-bold">Quick Actions</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-5">
                                    <a href="{{ route('sales.create') }}" class="btn btn-light-primary btn-flex align-items-center p-5 w-100 text-start">
                                        <i class="fa-solid fa-cart-plus fs-2 me-4"></i>
                                        <span class="d-flex flex-column align-items-start">
                                            <span class="fs-5 fw-bold">Record a New Sale</span>
                                            <span class="fs-7 text-muted">Deduct stock and send email invoice</span>
                                        </span>
                                    </a>
                                    <a href="{{ route('products.index') }}" class="btn btn-light-success btn-flex align-items-center p-5 w-100 text-start">
                                        <i class="fa-solid fa-boxes-stacked fs-2 me-4"></i>
                                        <span class="d-flex flex-column align-items-start">
                                            <span class="fs-5 fw-bold">Manage Products</span>
                                            <span class="fs-7 text-muted">View and update stock levels</span>
                                        </span>
                                    </a>
                                    <a href="{{ route('customers.index') }}" class="btn btn-light-warning btn-flex align-items-center p-5 w-100 text-start">
                                        <i class="fa-solid fa-users fs-2 me-4"></i>
                                        <span class="d-flex flex-column align-items-start">
                                            <span class="fs-5 fw-bold">CRM Customers</span>
                                            <span class="fs-7 text-muted">Manage lost customers and emails</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->

    @push('script')
    @endpush
@endsection
