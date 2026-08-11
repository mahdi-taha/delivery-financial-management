<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'driver_percentage',
        'is_active',
        'driver_type',
        'payment_type',
        'salary',
        'notes'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }


    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function pendingSettlementOrders()
    {
        return $this->orders()
            ->whereHas('settlements', function ($query) {
                $query->where('status', 'pending');
            });
    }
}
