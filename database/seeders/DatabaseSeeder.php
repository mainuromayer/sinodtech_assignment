<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed permissions and admin user
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
        ]);

        // 1. Seed Branches
        $dhaka = Branch::create(['name' => 'Dhaka Branch', 'location' => 'Gulshan, Dhaka']);
        $chittagong = Branch::create(['name' => 'Chittagong Branch', 'location' => 'GEC Circle, Chittagong']);
        $sylhet = Branch::create(['name' => 'Sylhet Branch', 'location' => 'Zindabazar, Sylhet']);

        // 2. Seed Products
        $laptop = Product::create(['name' => 'Laptop', 'sku' => 'PROD-LAPTOP', 'price' => 80000.00]);
        $phone = Product::create(['name' => 'Smartphone', 'sku' => 'PROD-PHONE', 'price' => 30000.00]);
        $keyboard = Product::create(['name' => 'Keyboard', 'sku' => 'PROD-KEYBOARD', 'price' => 1500.00]);
        $mouse = Product::create(['name' => 'Mouse', 'sku' => 'PROD-MOUSE', 'price' => 800.00]);
        $monitor = Product::create(['name' => 'Monitor', 'sku' => 'PROD-MONITOR', 'price' => 15000.00]);

        // 3. Assign Stock to Branches
        $dhaka->products()->attach([
            $laptop->id => ['stock_quantity' => 10],
            $phone->id => ['stock_quantity' => 20],
            $keyboard->id => ['stock_quantity' => 50],
            $mouse->id => ['stock_quantity' => 50],
            $monitor->id => ['stock_quantity' => 15],
        ]);

        $chittagong->products()->attach([
            $laptop->id => ['stock_quantity' => 5],
            $phone->id => ['stock_quantity' => 10],
            $keyboard->id => ['stock_quantity' => 30],
            $mouse->id => ['stock_quantity' => 30],
            $monitor->id => ['stock_quantity' => 5],
        ]);

        $sylhet->products()->attach([
            $laptop->id => ['stock_quantity' => 2],
            $phone->id => ['stock_quantity' => 5],
            $keyboard->id => ['stock_quantity' => 10],
            $mouse->id => ['stock_quantity' => 10],
            $monitor->id => ['stock_quantity' => 2],
        ]);

        // 4. Seed Employees
        $john = Employee::create(['name' => 'John Doe', 'email' => 'john@example.com', 'kpi_score' => 10]);
        $jane = Employee::create(['name' => 'Jane Smith', 'email' => 'jane@example.com', 'kpi_score' => 5]);
        $bob = Employee::create(['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'kpi_score' => 0]);

        // 5. Seed Customers
        $alice = Customer::create([
            'name' => 'Alice Brown',
            'email' => 'alice@example.com',
            'phone' => '01711111111',
            'created_at' => Carbon::now()->subDays(30)
        ]);

        $charlie = Customer::create([
            'name' => 'Charlie Green',
            'email' => 'charlie@example.com',
            'phone' => '01822222222',
            'created_at' => Carbon::now()->subDays(120)
        ]);

        $david = Customer::create([
            'name' => 'David White',
            'email' => 'david@example.com',
            'phone' => '01933333333',
            'created_at' => Carbon::now()->subDays(120) // Never purchased, created 120 days ago (Lost)
        ]);

        $eva = Customer::create([
            'name' => 'Eva Black',
            'email' => 'eva@example.com',
            'phone' => '01544444444',
            'assigned_employee_id' => $john->id, // Assigned to John Doe (Lost)
            'created_at' => Carbon::now()->subDays(160)
        ]);

        // 6. Seed Sales History
        // Alice: Purchase 10 days ago at Dhaka Branch, assisted by John Doe
        $sale1 = Sale::create([
            'customer_id' => $alice->id,
            'branch_id' => $dhaka->id,
            'employee_id' => $john->id,
            'total_amount' => 81500.00,
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);
        SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $laptop->id,
            'quantity' => 1,
            'price' => 80000.00,
            'created_at' => Carbon::now()->subDays(10),
        ]);
        SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $keyboard->id,
            'quantity' => 1,
            'price' => 1500.00,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        // Charlie: Purchase 100 days ago at Chittagong Branch, assisted by Jane Smith
        $sale2 = Sale::create([
            'customer_id' => $charlie->id,
            'branch_id' => $chittagong->id,
            'employee_id' => $jane->id,
            'total_amount' => 30000.00,
            'created_at' => Carbon::now()->subDays(100),
            'updated_at' => Carbon::now()->subDays(100),
        ]);
        SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $phone->id,
            'quantity' => 1,
            'price' => 30000.00,
            'created_at' => Carbon::now()->subDays(100),
        ]);

        // Eva: Purchase 150 days ago at Sylhet Branch, assisted by Bob Johnson
        $sale3 = Sale::create([
            'customer_id' => $eva->id,
            'branch_id' => $sylhet->id,
            'employee_id' => $bob->id,
            'total_amount' => 1600.00,
            'created_at' => Carbon::now()->subDays(150),
            'updated_at' => Carbon::now()->subDays(150),
        ]);
        SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $mouse->id,
            'quantity' => 2,
            'price' => 800.00,
            'created_at' => Carbon::now()->subDays(150),
        ]);
    }
}
