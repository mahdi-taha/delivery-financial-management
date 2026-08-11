<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settlement extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'driver_percentage',
        'date',
        'total_orders',
        'driver_total',
        'delivery_total',
        'company_total',
        'contract_company_total',
        'subtotal',
        'status',
        'notes'
    ];
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
        public function transaction()
    {
        return $this->hasOne(FinancialTransaction::class);
    }
        protected static function booted()
    {
        static::created(function ($settlement) {
            $settlement->settlement_num = 'ST-' . str_pad($settlement->id, 6, '0', STR_PAD_LEFT);
            $settlement->save();
        });
    }
}
