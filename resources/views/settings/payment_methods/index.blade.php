<x-main page="Settings: Payment Methods">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.payment_methods') }}</h4>
                    <p class="text-muted mb-0">{{ __('common.manage_your_payment_methods') }}</p>
                </div>
                <a href="{{ route('payment_methods.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                   {{ __('common.add_payment_method') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead class="table-head">
                        <tr>
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $system = __('common.system');
                        @endphp
                        @forelse ($payment_methods as $payment_method)
                            <tr class="table-row">
                                <td>{{ $payment_method->name }} {!! $payment_method->is_default ? '<span class="badge bg-success">'.$system.'</span>' : '' !!}</td>
                                <td>
                                    <span class="badge bg-{{ $payment_method->is_active ? 'success' : 'danger' }}">
                                        @if ($payment_method->is_active)
                                            {{ __('common.active') }}
                                        @else
                                            {{ __('common.inactive') }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('payment_methods.edit', $payment_method->id) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span>{{ __('common.edit') }}</span>
                                        </a>
                                        <form action="{{ route('payment_methods.destroy', $payment_method->id) }}"
                                            method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                <span>{{ __('common.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-credit-card-fill fs-2 d-block mb-2"></i>
                                    No Payment Methods found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-main>
