<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'type',
        'amount',
        'amount_base',
        'currency_id',
        'exchange_rate',
        'direction',
        'status',
        'notes',
        'driver_id',
        'contract_company_id',
        'collection_id',
        'settlement_id',
        'user_id',
        'payment_method_id'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }


    public function contractCompany()
    {
        return $this->belongsTo(ContractCompany::class);
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->transaction_num = 'TRAN-' . str_pad($transaction->id, 6, '0', STR_PAD_LEFT);
            $transaction->save();
        });
    }


}
