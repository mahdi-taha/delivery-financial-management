<x-main page="Settings: Activity Logs">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-start">
            <div>
                <h3 class="mb-1">
                    {{ __('activity_logs.details.title') }}
                </h3>
            </div>
            <div>
                <a href="{{ route('activity-logs.index', $activityLog->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-square"></i>
                    {{ __('activity_logs.details.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    {{ __('activity_logs.details.user') }}
                </div>
                <div class="col-md-9">
                    {{ $activityLog->user?->name ?? __('activity_logs.details.system') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    {{ __('activity_logs.details.action') }}
                </div>
                <div class="col-md-9">
                    <span class="badge bg-primary">
                        {{ __('activity_logs.details.actions_list.' . $activityLog->action) }}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    {{ __('activity_logs.details.description') }}
                </div>
                <div class="col-md-9">
                    {{ $activityLog->description }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    {{ __('activity_logs.details.date') }}
                </div>
                <div class="col-md-9">
                    {{ \Carbon\Carbon::parse($activityLog->created_at)->format('d-m-Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>
    @if ($activityLog->changes)
        <div class="card shadow-sm my-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-head">
                            <tr>
                                <th>
                                    {{ __('activity_logs.details.field') }}
                                </th>
                                <th>
                                    {{ __('activity_logs.details.old_value') }}
                                </th>
                                <th>
                                    {{ __('activity_logs.details.new_value') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activityLog->changes as $field => $change)
                                @if ($field === 'id')
                                    @continue
                                @endif
                                <tr>
                                    <td class="fw-bold">
                                        @if ($field === 'contract_company_total')
                                            Partner Total
                                        @else
                                            {{ str_ends_with($field, '_id') ? Str::headline(Str::replaceLast('_id', '', $field)) : Str::headline($field) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array($field, ['icon', 'logo', 'image']) && $change['old'])
                                            <img src="{{ Storage::url($change['new']) }}" width="60"
                                                class="rounded border">
                                        @else
                                            @if (is_array($change['old']))
                                                {{ json_encode($change['old']) }}
                                            @else
                                                {{ \App\Helpers\ActivityHelper::displayValue($field, $change['old']) }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array($field, ['icon', 'logo', 'image']) && $change['new'])
                                            <img src="{{ Storage::url($change['new']) }}" width="60"
                                                class="rounded border">
                                        @else
                                            @if (is_array($change['new']))
                                                {{ json_encode($change['new']) }}
                                            @else
                                                {{ \App\Helpers\ActivityHelper::displayValue($field, $change['new']) }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</x-main>