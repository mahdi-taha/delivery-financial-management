<?php
namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:activity_logs', only: [
                'index',
                'show'
            ]),
        ];
    }
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->model, function ($query, $model) {
                $query->where('model_type', $model);
            })
            ->when($request->from, function ($query, $from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($request->to, function ($query, $to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view('settings.activity_logs.index', compact('logs'));
    }
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('settings.activity_logs.show', compact('activityLog'));
    }
}
