<x-main page="Reports">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container-fluid">
        <div class="card p-3 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('reports.partners_report') }}</h4>
                </div>
                <div>
                    <a href="{{ route('reports.partners.print', request()->query()) }}" target="_blank" class="btn btn-main">
                        {{ __('reports.print') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form method="GET">
                <div class="row">
                    <div class="col-md-2">
                        <label>{{ __('reports.from') }}</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('reports.to') }}</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('reports.partners') }}</label>
                        <select name="partners[]" multiple class="form-control select2" style="width: 100%">
                            @foreach ($partners as $partner)
                                <option value="{{ $partner->id }}" @if (in_array($partner->id, request('partners', []))) selected @endif>
                                    {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>{{ __('reports.sort_by') }}</label>
                        <select name="sort" class="form-control">
                            <option value="earnings">
                                {{ __('reports.earnings') }}
                            </option>
                            <option value="orders_count">
                               {{ __('reports.orders_count') }}
                            </option>
                            <option value="last_date" selected>
                                {{ __('reports.date') }}
                            </option>
                            <option value="partner_name">
                                {{ __('reports.partner_name') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>{{ __('reports.order') }}</label>
                        <select name="direction" class="form-control">
                            <option value="desc">
                                {{ __('reports.desc') }}
                            </option>
                            <option value="asc">
                                {{ __('reports.asc') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button class="btn btn-main">
                            {{ __('reports.filter') }}
                        </button>
                        <a href="{{ route('reports.partners') }}" class="btn btn-secondary">
                            {{ __('reports.reset') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <h6>{{ __('reports.partners') }}</h6>
                    <h3>
                        {{ $summary['partners_count'] }}
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h6>{{ __('reports.orders') }}</h6>
                    <h3>
                        {{ $summary['orders_count'] }}
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h6>{{ __('reports.total_earnings') }}</h6>
                    <h3>
                        {{ number_format($summary['earnings'], 2) }}
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h6>{{ __('reports.currency') }}</h6>
                    <h3>
                        {{ $currency->symbol }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                {{ __('reports.partner') }}
                            </th>
                            <th>
                                {{ __('reports.orders_count') }}
                            </th>
                            <th>
                                {{ __('reports.earnings') }}
                            </th>
                            <th>
                                {{ __('reports.date') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partnersReport as $row)
                            <tr>
                                <td>
                                    {{ $row->partner_name }}
                                </td>
                                <td>
                                    {{ $row->orders_count }}
                                </td>
                                <td>
                                    {{ number_format($row->earnings, 2) }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-main>
