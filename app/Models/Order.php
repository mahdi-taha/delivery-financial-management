<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_fee',
        'delivery_fee_base',
        'contract_company_percentage',
        'contract_company_fixed',
        'contract_company_amount',
        'driver_amount',
        'contract_company_amount_base',
        'driver_amount_base',
        'company_amount',
        'company_amount_base',
        'exchange_rate',
        'currency_id',
        'driver_id',
        'contract_company_id'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }


    public function contractCompany()
    {
        return $this->belongsTo(ContractCompany::class);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    public function settlements()
    {
        return $this->belongsToMany(Settlement::class);
    }

    protected static function booted()
    {
        static::created(function ($order) {
            $order->order_num = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
            $order->save();
        });
    }
}
