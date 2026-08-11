<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
    ];
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
