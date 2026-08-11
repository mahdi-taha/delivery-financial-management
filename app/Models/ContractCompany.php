<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractCompany extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'fixed_fee',
        'percentage',
        'phone',
        'is_active',
        'fee_type'
    ];


    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function transactions()
    {
        return $this->hasMany(
            FinancialTransaction::class
        );
    }
}
