@extends('backend.app')

@section('title', 'Record New Sale')

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
                        Record New Sale
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('sales.index') }}" class="text-muted text-hover-primary">Sales History</a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            Record Sale
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
                    <div class="card-header align-items-center py-5">
                        <h3 class="card-title fw-bold text-dark"><i class="fa-solid fa-cart-plus text-primary me-2"></i>Record Sale Transaction</h3>
                    </div>
                    <div class="card-body pt-0">
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="fa-solid fa-circle-xmark fs-2x text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="fw-bold mb-1">Please correct the errors below:</h4>
                                    <ul class="mb-0 pl-4">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('sales.store') }}" method="POST">
                            @csrf
                            <div class="row g-5 mb-8">
                                <!-- Branch Selection -->
                                <div class="col-md-4">
                                    <label for="branch_id" class="form-label required">Select Branch</label>
                                    <select name="branch_id" id="branch_id" required onchange="updateBranchStock()" class="form-select form-select-solid">
                                        <option value="">-- Choose Branch --</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Customer Selection -->
                                <div class="col-md-4">
                                    <label for="customer_id" class="form-label">Select Customer (Optional)</label>
                                    <select name="customer_id" id="customer_id" class="form-select form-select-solid">
                                        <option value="">-- Walk-in Customer --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Employee Selection -->
                                <div class="col-md-4">
                                    <label for="employee_id" class="form-label">Assisted By (Optional)</label>
                                    <select name="employee_id" id="employee_id" class="form-select form-select-solid">
                                        <option value="">-- Select Employee --</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Products Section -->
                            <div class="border-top pt-8 mb-8">
                                <div class="d-flex justify-content-between align-items-center mb-6">
                                    <h4 class="fw-bold text-dark mb-0">Products & Quantities</h4>
                                    <button type="button" onclick="addProductRow()" class="btn btn-light-primary btn-sm">
                                        <i class="fa-solid fa-plus me-1"></i> Add Product
                                    </button>
                                </div>

                                <div id="product-rows" class="d-flex flex-column gap-5">
                                    <!-- Initial Product Row -->
                                    <div class="product-row row g-5 align-items-end p-5 bg-light rounded shadow-sm border border-light">
                                        <div class="col-md-6">
                                            <label class="form-label required">Product</label>
                                            <select name="items[0][product_id]" required onchange="updateRowPriceAndStock(this)" class="product-select form-select form-select-solid">
                                                <option value="">-- Select Product --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (৳{{ number_format($product->price, 2) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Available Stock</label>
                                            <input type="text" readonly class="stock-display form-control form-control-solid text-gray-500" value="0">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label required">Quantity</label>
                                            <input type="number" name="items[0][quantity]" required min="1" oninput="calculateTotal()" class="quantity-input form-control form-control-solid" value="1">
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button" onclick="removeProductRow(this)" class="btn btn-light-danger btn-sm w-100">
                                                <i class="fa-solid fa-trash me-1"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount & Submission -->
                            <div class="border-top pt-8 d-flex flex-column flex-md-row justify-content-between align-items-center gap-5">
                                <div class="fs-4 fw-bold text-gray-700">
                                    Total Amount: <span class="text-success fs-3 fw-bolder ms-2" id="total-amount-display">৳0.00</span>
                                </div>
                                <button type="submit" class="btn btn-success px-8 py-3">
                                    Record Sale & Send Invoice
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->

    <script>
        // Store branch stock data in a JavaScript object
        const branchStockData = {};
        @foreach($branches as $branch)
            branchStockData[{{ $branch->id }}] = {
                @foreach($branch->products as $product)
                    {{ $product->id }}: {{ $product->pivot->stock_quantity }},
                @endforeach
            };
        @endforeach

        let rowCount = 1;

        function addProductRow() {
            const container = document.getElementById('product-rows');
            const newRow = document.createElement('div');
            newRow.className = 'product-row row g-5 align-items-end p-5 bg-light rounded shadow-sm border border-light';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label required">Product</label>
                    <select name="items[\${rowCount}][product_id]" required onchange="updateRowPriceAndStock(this)" class="product-select form-select form-select-solid">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (৳{{ number_format($product->price, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Available Stock</label>
                    <input type="text" readonly class="stock-display form-control form-control-solid text-gray-500" value="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label required">Quantity</label>
                    <input type="number" name="items[\${rowCount}][quantity]" required min="1" oninput="calculateTotal()" class="quantity-input form-control form-control-solid" value="1">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" onclick="removeProductRow(this)" class="btn btn-light-danger btn-sm w-100">
                        <i class="fa-solid fa-trash me-1"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(newRow);
            rowCount++;
            updateBranchStock();
        }

        function removeProductRow(button) {
            const row = button.closest('.product-row');
            row.remove();
            calculateTotal();
        }

        function updateBranchStock() {
            const branchSelect = document.getElementById('branch_id');
            const branchId = branchSelect.value;
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const stockDisplay = row.querySelector('.stock-display');
                const productId = productSelect.value;

                if (branchId && productId) {
                    const stock = branchStockData[branchId] && branchStockData[branchId][productId] !== undefined
                        ? branchStockData[branchId][productId]
                        : 0;
                    stockDisplay.value = stock;
                } else {
                    stockDisplay.value = 0;
                }
            });
            calculateTotal();
        }

        function updateRowPriceAndStock(selectElement) {
            updateBranchStock();
        }

        function calculateTotal() {
            let total = 0;
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const selectedOption = productSelect.options[productSelect.selectedIndex];

                if (selectedOption && selectedOption.value) {
                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    const quantity = parseInt(quantityInput.value) || 0;
                    total += price * quantity;
                }
            });

            document.getElementById('total-amount-display').innerText = '৳' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    </script>
@endsection