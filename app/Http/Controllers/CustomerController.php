<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Http\Requests\AssignEmployeeRequest;
use App\Mail\ReengagementMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $days = $request->input('days', 90);

        // All customers with purchase history details
        $customers = Customer::with(['sales', 'assignedEmployee'])->get();

        // Lost customers
        $lostCustomers = Customer::lost($days)->with(['sales', 'assignedEmployee'])->get();

        $employees = Employee::all();

        return view('customers.index', compact('customers', 'lostCustomers', 'employees', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function assignEmployee(AssignEmployeeRequest $request)
    {
        $customer = Customer::findOrFail($request->input('customer_id'));
        $customer->update([
            'assigned_employee_id' => $request->input('employee_id')
        ]);

        return redirect()->route('customers.index')->with('success', 'Employee assigned to customer successfully.');
    }

    public function reengage(Request $request, Customer $customer)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Send promotional email
        if ($customer->email) {
            try {
                Mail::to($customer->email)->send(new ReengagementMail($customer, $request->input('message')));
            } catch (\Exception $e) {
                logger()->error('Failed to send re-engagement email: ' . $e->getMessage());
                return redirect()->route('customers.index')->with('error', 'Failed to send email: ' . $e->getMessage());
            }
        }

        return redirect()->route('customers.index')->with('success', 'Re-engagement email sent successfully.');
    }
}
