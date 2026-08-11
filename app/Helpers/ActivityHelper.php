<?php
namespace App\Helpers;
use App\Models\Account;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Currency;
use App\Models\Domain;
use App\Models\Driver;
use App\Models\Host;
use App\Models\PaymentMethod;
use App\Models\ProjectType;
use App\Models\Platform;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Carbon;
class ActivityHelper
{
    public static function displayValue($field, $value)
    {
        if ($field === 'id') {
            return null;
        }
        if (in_array($field, [
            'is_active',
            'auto_renew',
        ])) {
            return $value == 1 ? 'Yes' : 'No';
        }
        if (!$value) {
            return '-';
        }
        // Format dates
        if (self::isDate($value)) {
            return \Carbon\Carbon::parse($value)
                ->format('d-m-Y H:i');
        }
        if ($value && str_ends_with($field, '_at')) {
            return \Carbon\Carbon::parse($value)
                ->format('d-m-Y H:i');
        }
        return match ($field) {
            'driver_id' =>
            Driver::find($value)?->name ?? $value,
            'currency_id' =>
            Currency::find($value)?->symbol ?? $value,
            'payment_method_id' =>
            PaymentMethod::find($value)?->name ?? $value,
            'user_id' =>
            User::find($value)?->name ?? $value,
            default => $value,
        };
    }
    private static function isDate($value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        try {
            \Carbon\Carbon::parse($value);
            return preg_match('/[-:\/]/', $value);
        } catch (\Exception $e) {
            return false;
        }
    }
}
