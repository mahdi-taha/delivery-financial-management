<x-main page="Settings: Currencies">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1"> {{ __('common.currencies') }}</h4>
                    <p class="text-muted mb-0">{{ __('common.manage_your_currencies') }}</p>
                </div>
                <a href="{{ route('currencies.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('common.add_currency') }}
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
                            <th> {{ __('common.name') }}</th>
                            <th> {{ __('common.symbol') }}</th>
                            <th> {{ __('common.rate') }}</th>
                            <th> {{ __('common.input_mode') }}</th>
                            <th> {{ __('common.rounding_unit') }}</th>
                            <th width="80"> {{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $system = __('common.system');
                        @endphp
                        @forelse ($currencies as $currency)
                            <tr class="table-row">
                                <td>{{ $currency->name }} {!! $currency->is_default ? '<span class="badge bg-success">'. $system .'</span>' : '' !!}</td>
                                <td>{{ $currency->symbol }}</td>
                                <td>{{ rtrim(rtrim(number_format($currency->rate, 6), '0'), '.') }}</td>
                                <td>{{ $currency->input_mode }}</td>
                                <td>{{ $currency->rounding_unit }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('currencies.edit', $currency->id) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span>{{ __('common.edit') }}</span>
                                        </a>
                                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST"
                                            class="delete-form d-inline">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-currency-exchange fs-2 d-block mb-2"></i>
                                    {{ __('common.no_currencies_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-main>
