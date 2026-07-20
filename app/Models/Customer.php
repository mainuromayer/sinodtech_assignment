<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'assigned_employee_id'];

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get purchase frequency (number of purchases).
     */
    public function getPurchaseFrequencyAttribute(): int
    {
        return $this->sales()->count();
    }

    /**
     * Get last purchase date.
     */
    public function getLastPurchaseDateAttribute(): ?Carbon
    {
        $lastSale = $this->sales()->latest()->first();
        return $lastSale ? $lastSale->created_at : null;
    }

    /**
     * Check if customer is "lost" (no purchase within configurable days, default 90).
     */
    public function isLost(int $days = 90): bool
    {
        $lastPurchase = $this->last_purchase_date;
        if (!$lastPurchase) {
            // If they have never purchased, but were created more than $days ago, they are lost.
            return $this->created_at->diffInDays(now()) >= $days;
        }
        return $lastPurchase->diffInDays(now()) >= $days;
    }

    /**
     * Scope to get lost customers.
     */
    public function scopeLost($query, int $days = 90)
    {
        return $query->where(function ($q) use ($days) {
            // Never purchased and created >= $days ago
            $q->whereDoesntHave('sales')
              ->where('created_at', '<=', now()->subDays($days));
        })->orWhere(function ($q) use ($days) {
            // Last purchase >= $days ago
            $q->whereHas('sales', function ($sub) use ($days) {
                $sub->select('customer_id')
                    ->groupBy('customer_id')
                    ->havingRaw('MAX(created_at) <= ?', [now()->subDays($days)]);
            });
        });
    }
}
