# Sales, Inventory & CRM System

A robust, modern business application built using Laravel (PHP) and Blade. This system features Sales & Inventory Management, Customer Relationship Management (CRM), Multi-Branch Support, Email Invoices, and a REST API for third-party e-commerce integration.

---

## Completed Features

### 1. Sales & Inventory Management

- **Product Catalog**: Manage products with Name, SKU, and Price.
- **Multi-Branch Support**:
    - Manage multiple store locations.
    - Maintain branch-specific inventory.
    - Record sales by branch.
- **Inventory Control**:
    - Automatically deduct stock quantity when a product is sold.
    - Prevent sales when available stock in the selected branch is insufficient (validated via FormRequests).

### 2. Customer Relationship Management (CRM)

- **Customer Purchase History**: Track purchase records, purchase frequency, and last purchase date for each customer.
- **Lost Customer Detection**: Automatically identify inactive ("lost") customers who have not made a purchase within a configurable period (default: 90 days).
- **Employee Assignment**: Assign inactive/lost customers to employees for follow-up.
- **KPI Tracking**: Automatically increase the assigned employee's KPI score by 10 points when an assigned inactive customer makes a new purchase.
- **Customer Re-engagement**: Send promotional emails to inactive customers directly from the CRM dashboard.

### 3. Bonus Features

- **Email Invoices**: Automatically generate and send an HTML invoice to the customer after a successful purchase.
- **E-Commerce Integration API**:
    - `GET /api/products`: Exposes product information (SKU, Name, Price, and total available stock across all branches).
    - `GET /api/products/{sku}`: Exposes details for a specific product by SKU.

---

## Technical Stack

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade & Tailwind CSS (via CDN)
- **Mail**: SMTP (configured for Mailtrap or local log)

---

## Installation & Setup Instructions

### 1. Prerequisites

Ensure you have the following installed:

- PHP >= 8.2
- Composer
- MySQL
- Laragon (or any local server environment)

### 2. Clone & Install Dependencies

Navigate to the project directory and install PHP dependencies:

```bash
composer install
```

### 3. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
copy .env.example .env
```

Configure your database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sinodtech_assignment
DB_USERNAME=root
DB_PASSWORD=
```

To test email features, configure your SMTP settings (e.g., Mailtrap) in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bizmanager.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Database Migrations & Seeders

Run the migrations and seed the database with realistic sample data:

```bash
php artisan migrate:fresh --seed
```

This will seed:

- **3 Branches**: Dhaka, Chittagong, and Sylhet.
- **5 Products** with branch-specific stock levels.
- **3 Employees** with initial KPI scores.
- **4 Customers** (including active and lost customers).
- **Historical Sales & Transactions** to populate purchase histories.

### 5. Run the Application

Start the local development server:

```bash
php artisan serve
```

Access the application in your browser at `http://127.0.0.1:8000`.

---

## API Endpoints

- **List Products**: `GET http://127.0.0.1:8000/api/products`
- **Product Details**: `GET http://127.0.0.1:8000/api/products/{sku}`
