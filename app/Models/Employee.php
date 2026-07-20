<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'kpi_score'];

    public function assignedCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'assigned_employee_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
