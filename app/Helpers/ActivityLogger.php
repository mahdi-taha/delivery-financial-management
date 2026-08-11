<?php
namespace App\Helpers;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
class ActivityLogger
{
    public static function log(
        string $action,
        $model,
        string $description,
        array $old = [],
        array $new = []
    ) {
        $changes = null;
        if (!self::isSensitive($model)) {
            $changes = self::makeChanges($old, $new, $model);
        }
        $log =  ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description,
            'changes' => $changes,
        ]);
    }
    private static function isSensitive($model)
    {
        return in_array(
            class_basename($model),
            [
                'User',
                'Account',
                'Role'
            ]
        );
    }
    private static function makeChanges($old, $new)
    {
        $changes = [];
        $hidden = [
            'password',
            'remember_token',
            'token',
            'api_key',
            'secret',
        ];
        foreach ($new as $key => $value) {
            if (in_array($key, $hidden)) {
                continue;
            }
            $oldValue = $old[$key] ?? null;
            if ($oldValue != $value) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $value,
                ];
            }
        }
        return empty($changes) ? null : $changes;
    }
}
