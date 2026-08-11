<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{

    use HasFactory;
    protected $fillable = [
        'date',
        'payment_method_id',
        'received_amount',
        'driver_amount',
        'company_amount',
        'received_amount_base',
        'driver_amount_base',
        'company_amount_base',
        'currency_id',
        'driver_id',
        'exchange_rate',
        'status',
        'notes',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
    protected static function booted()
    {
        static::created(function ($collection) {
            $collection->collection_num = 'COL-' . str_pad($collection->id, 6, '0', STR_PAD_LEFT);
            $collection->save();
        });
    }
}
