@extends('backend.app')

@section('title', 'Product Catalog & Inventory')

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
                        Product Catalog & Inventory
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            Products
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
                    <!-- Product Catalog -->
                    <div class="col-12">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <h3 class="card-title fw-bold text-dark">
                                    <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Product Catalog
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_product">
                                        <i class="fa-solid fa-plus me-2"></i>Add Product
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
                                                <th class="min-w-150px">Product Name</th>
                                                <th class="min-w-100px">SKU</th>
                                                <th class="min-w-100px">Price</th>
                                                <th class="min-w-150px">Branch Stock</th>
                                                <th class="min-w-100px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @forelse($products as $product)
                                                @php
                                                    $stockArray = [];
                                                    foreach($product->branches as $b) {
                                                        $stockArray[] = ['branch_id' => $b->id, 'stock_quantity' => $b->pivot->stock_quantity];
                                                    }
                                                    $stockJson = json_encode($stockArray);
                                                @endphp
                                                <tr>
                                                    <td class="text-gray-800 fw-bold">{{ $product->name }}</td>
                                                    <td><code>{{ $product->sku }}</code></td>
                                                    <td class="text-success font-semibold">৳{{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            @foreach($product->branches as $branch)
                                                                <div class="d-flex justify-content-between fs-7">
                                                                    <span class="text-gray-500">{{ $branch->name }}:</span>
                                                                    <span class="fw-bold {{ $branch->pivot->stock_quantity > 5 ? 'text-success' : ($branch->pivot->stock_quantity > 0 ? 'text-warning' : 'text-danger') }}">
                                                                        {{ $branch->pivot->stock_quantity }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                            @if($product->branches->isEmpty())
                                                                <span class="text-danger fs-7">Out of Stock</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button onclick='openEditModal({!! json_encode($product->only(['id', 'name', 'sku', 'price'])) !!}, {!! $stockJson !!})' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Edit">
                                                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                                                            </button>
                                                            <form action="{{ route('products.destroy', $product) }}" method="POST" id="delete-form-{{ $product->id }}" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" onclick="confirmDelete({{ $product->id }})" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete">
                                                                    <i class="fa-solid fa-trash text-danger"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-5">No products found.</td>
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

    <!--begin::Modal - Add Product-->
    <div class="modal fade" id="kt_modal_add_product" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add New Product</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="{{ route('products.store') }}" method="POST" class="form">
                        @csrf
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-5">
                                <label for="prod_name" class="form-label required">Product Name</label>
                                <input type="text" name="name" id="prod_name" required class="form-control form-control-solid" placeholder="e.g. iPhone 15">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="prod_sku" class="form-label required">SKU</label>
                                <input type="text" name="sku" id="prod_sku" required class="form-control form-control-solid" placeholder="e.g. IPH15-128">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="prod_price" class="form-label required">Price (৳)</label>
                                <input type="number" step="0.01" name="price" id="prod_price" required class="form-control form-control-solid" placeholder="e.g. 95000">
                            </div>
                            <div class="border-top pt-5 mb-5">
                                <h4 class="fw-bold fs-6 mb-3">Initial Branch Stock</h4>
                                <div class="d-flex flex-column gap-4">
                                    @foreach($branches as $branch)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="stock_{{ $branch->id }}" class="form-label mb-0">{{ $branch->name }}</label>
                                            <input type="number" name="branch_stock[{{ $branch->id }}]" id="stock_{{ $branch->id }}" value="0" min="0" class="form-control form-control-solid w-150px text-end">
                                        </div>
                                    @endforeach
                                </div>
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
    <!--end::Modal - Add Product-->

    <!--begin::Modal - Edit Product-->
    <div class="modal fade" id="kt_modal_edit_product" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Edit Product</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="" method="POST" id="edit_product_form" class="form">
                        @csrf
                        @method('PUT')
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-5">
                                <label for="edit_name" class="form-label required">Product Name</label>
                                <input type="text" name="name" id="edit_name" required class="form-control form-control-solid">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="edit_sku" class="form-label required">SKU</label>
                                <input type="text" name="sku" id="edit_sku" required class="form-control form-control-solid">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="edit_price" class="form-label required">Price (৳)</label>
                                <input type="number" step="0.01" name="price" id="edit_price" required class="form-control form-control-solid">
                            </div>
                            <div class="border-top pt-5 mb-5">
                                <h4 class="fw-bold fs-6 mb-3">Branch Stock Levels</h4>
                                <div class="d-flex flex-column gap-4">
                                    @foreach($branches as $branch)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="edit_stock_{{ $branch->id }}" class="form-label mb-0">{{ $branch->name }}</label>
                                            <input type="number" name="branch_stock[{{ $branch->id }}]" id="edit_stock_{{ $branch->id }}" value="0" min="0" class="edit-branch-stock form-control form-control-solid w-150px text-end">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Edit Product-->

    <script>
        function openEditModal(product, branchStock) {
            let url = "{{ route('products.update', ['product' => '__id__']) }}".replace('__id__', product.id);
            document.getElementById('edit_product_form').setAttribute('action', url);

            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_sku').value = product.sku;
            document.getElementById('edit_price').value = product.price;

            // Reset all stock levels first
            document.querySelectorAll('.edit-branch-stock').forEach(input => {
                input.value = 0;
            });

            // Set stock levels
            branchStock.forEach(item => {
                let stockInput = document.getElementById(`edit_stock_${item.branch_id}`);
                if (stockInput) {
                    stockInput.value = item.stock_quantity;
                }
            });

            // Show Bootstrap Modal
            let myModal = new bootstrap.Modal(document.getElementById('kt_modal_edit_product'));
            myModal.show();
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f1416c',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endsection