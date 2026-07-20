@extends('backend.app')

@section('title', 'Customer Relationship Management (CRM)')

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
                        CRM & Lost Customers
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb fw-semibold fs-base my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item text-dark">
                            CRM
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
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                        <i class="fa-solid fa-circle-check fs-2x text-success me-4"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                        <i class="fa-solid fa-circle-xmark fs-2x text-danger me-4"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Lost Customers List -->
                <div class="card card-flush shadow-sm mb-8">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fa-solid fa-user-slash text-danger me-2"></i>Lost Customers (Inactive for {{ $days }}+ Days)
                        </h3>
                        <div class="card-toolbar gap-3">
                            <form action="{{ route('customers.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                <span class="fs-7 text-muted fw-bold">Period (Days):</span>
                                <input type="number" name="days" value="{{ $days }}" min="1" class="form-control form-control-solid w-80px form-control-sm">
                                <button type="submit" class="btn btn-light-warning btn-sm">Update</button>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_assign_employee">
                                <i class="fa-solid fa-user-tag me-1"></i>Assign Employee
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-150px">Customer Name</th>
                                        <th class="min-w-150px">Email / Phone</th>
                                        <th class="min-w-150px">Last Purchase Date</th>
                                        <th class="min-w-150px">Assigned Employee</th>
                                        <th class="min-w-100px text-end">Re-engage</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse($lostCustomers as $customer)
                                        <tr>
                                            <td class="text-gray-800 fw-bold">{{ $customer->name }}</td>
                                            <td>
                                                <div>{{ $customer->email }}</div>
                                                <div class="text-muted fs-7">{{ $customer->phone ?? 'No Phone' }}</div>
                                            </td>
                                            <td>
                                                {{ $customer->last_purchase_date ? $customer->last_purchase_date->format('Y-m-d') . ' (' . $customer->last_purchase_date->diffForHumans() . ')' : 'Never Purchased' }}
                                            </td>
                                            <td>
                                                @if($customer->assignedEmployee)
                                                    <span class="badge badge-light-success px-3 py-2">
                                                        {{ $customer->assignedEmployee->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button onclick='openReengageModal({!! json_encode($customer->only(['id', 'name'])) !!})' class="btn btn-light-danger btn-sm">
                                                    <i class="fa-solid fa-paper-plane me-1"></i> Send Promo
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No lost customers detected.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- All Customers Purchase History -->
                <div class="card card-flush shadow-sm">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fa-solid fa-users text-primary me-2"></i>Customer Purchase History & Frequency
                        </h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">
                                <i class="fa-solid fa-user-plus me-1"></i>Add Customer
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-150px">Customer Name</th>
                                        <th class="min-w-150px">Email</th>
                                        <th class="min-w-100px">Purchase Frequency</th>
                                        <th class="min-w-150px">Last Purchase Date</th>
                                        <th class="min-w-100px">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @forelse($customers as $customer)
                                        @php
                                            $isLost = $customer->isLost($days);
                                        @endphp
                                        <tr>
                                            <td class="text-gray-800 fw-bold">{{ $customer->name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>
                                                <span class="badge badge-light-primary px-3 py-2">
                                                    {{ $customer->purchase_frequency }} times
                                                </span>
                                            </td>
                                            <td>
                                                {{ $customer->last_purchase_date ? $customer->last_purchase_date->format('Y-m-d') : 'Never' }}
                                            </td>
                                            <td>
                                                @if($isLost)
                                                    <span class="badge badge-light-danger px-3 py-2">Lost</span>
                                                @else
                                                    <span class="badge badge-light-success px-3 py-2">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5">No customers found.</td>
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

    <!--begin::Modal - Add Customer-->
    <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add New Customer</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="{{ route('customers.store') }}" method="POST" class="form">
                        @csrf
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-5">
                                <label for="cust_name" class="form-label required">Customer Name</label>
                                <input type="text" name="name" id="cust_name" required class="form-control form-control-solid">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="cust_email" class="form-label required">Email</label>
                                <input type="email" name="email" id="cust_email" required class="form-control form-control-solid">
                            </div>
                            <div class="fv-row mb-5">
                                <label for="cust_phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" id="cust_phone" class="form-control form-control-solid">
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
    <!--end::Modal - Add Customer-->

    <!--begin::Modal - Assign Employee-->
    <div class="modal fade" id="kt_modal_assign_employee" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Assign Employee to Lost Customer</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="{{ route('customers.assign-employee') }}" method="POST" class="form">
                        @csrf
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-5">
                                <label for="assign_customer_id" class="form-label required">Select Lost Customer</label>
                                <select name="customer_id" id="assign_customer_id" required class="form-select form-select-solid">
                                    <option value="">-- Choose Customer --</option>
                                    @foreach($lostCustomers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} (Last: {{ $customer->last_purchase_date ? $customer->last_purchase_date->format('Y-m-d') : 'Never' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row mb-5">
                                <label for="assign_employee_id" class="form-label required">Select Employee</label>
                                <select name="employee_id" id="assign_employee_id" required class="form-select form-select-solid">
                                    <option value="">-- Choose Employee --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
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
    <!--end::Modal - Assign Employee-->

    <!--begin::Modal - Send Promo Email-->
    <div class="modal fade" id="kt_modal_send_promo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Send Promotional Offer</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark fs-1"></i>
                    </button>
                </div>
                <div class="modal-body px-5 my-7">
                    <form action="" method="POST" id="reengage_form" class="form">
                        @csrf
                        <div class="d-flex flex-column scroll-y px-5 px-lg-10" data-kt-scroll="true" data-kt-scroll-max-height="auto" data-kt-scroll-offset="300px">
                            <div class="mb-5 fs-5">
                                Re-engaging customer: <span class="fw-bold text-primary" id="reengage_customer_name"></span>
                            </div>
                            <div class="fv-row mb-5">
                                <label for="promo_message" class="form-label required">Promotional Message</label>
                                <textarea name="message" id="promo_message" rows="4" required class="form-control form-control-solid" placeholder="Enter promotional offer or message..."></textarea>
                            </div>
                        </div>
                        <div class="text-center pt-10">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-primary">Send Email</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Send Promo Email-->

    <script>
        function openReengageModal(customer) {
            let url = "{{ route('customers.reengage', ['customer' => '__id__']) }}".replace('__id__', customer.id);
            document.getElementById('reengage_form').setAttribute('action', url);
            document.getElementById('reengage_customer_name').innerText = customer.name;

            let myModal = new bootstrap.Modal(document.getElementById('kt_modal_send_promo'));
            myModal.show();
        }
    </script>
@endsection