@extends('backend.app')

@section('title', 'Branch Management')

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
                        Branch Management
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            Branches
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
                <div class="row g-5 g-xl-10">
                    <!-- Branch List -->
                    <div class="col-12">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <h3 class="card-title fw-bold text-dark">
                                    <i class="fa-solid fa-store text-primary me-2"></i>All Branches
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_branch">
                                        <i class="fa-solid fa-plus me-2"></i>Add Branch
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if(session('success'))
                                    <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                        <i class="fa-solid fa-circle-check fs-2x text-success me-4"></i>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-50px">ID</th>
                                                <th class="min-w-150px">Branch Name</th>
                                                <th class="min-w-150px">Location</th>
                                                <th class="min-w-100px">Products Stocked</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @forelse($branches as $branch)
                                                <tr>
                                                    <td>#{{ $branch->id }}</td>
                                                    <td class="text-gray-800 fw-bold">{{ $branch->name }}</td>
                                                    <td>{{ $branch->location }}</td>
                                                    <td>
                                                        <span class="badge badge-light-primary px-3 py-2">
                                                            {{ $branch->products_count }} Products
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-5">No branches found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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

    <!--begin::Modal - Add Branch-->
    <div class="modal fade" id="kt_modal_add_branch" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add New Branch</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="{{ route('branches.store') }}" method="POST" class="form">
                        @csrf
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-7">
                                <label for="name" class="form-label required fw-semibold fs-6 text-gray-700">Branch Name</label>
                                <input type="text" name="name" id="name" required class="form-control form-control-solid" placeholder="e.g. Dhaka">
                            </div>
                            <div class="fv-row mb-7">
                                <label for="location" class="form-label required fw-semibold fs-6 text-gray-700">Location</label>
                                <input type="text" name="location" id="location" required class="form-control form-control-solid" placeholder="e.g. Gulshan, Dhaka">
                            </div>
                        </div>
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Add Branch-->
@endsection