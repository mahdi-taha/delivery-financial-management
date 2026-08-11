<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <title>
        {{ __('reports.partners_report') }}
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .company img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .title {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #000;
            padding: 15px;
        }
    </style>
</head>
<body onload="window.print()">
        <div class="header">
        <div class="company">
            @if ($companyInfo?->logo)
                <img src="{{ asset('storage/' . $companyInfo->logo) }}">
            @endif
            <div>
                <h2>{{ $companyInfo->name ?? '' }}</h2>
            </div>
        </div>
        <div class="title">
            <h2>{{ __('reports.from') }}</h2>
            {{ __('reports.partners_report') }}:
            {{ request('from') ?? 'All' }}
            -
            {{ __('reports.to') }}:
            {{ request('to') ?? 'All' }}
        </div>
    </div>
    <div class="cards">
        <div class="card">
            {{ __('reports.partners') }}:
            {{ $summary['partners_count'] }}
        </div>
        <div class="card">
            {{ __('reports.orders') }}:
            {{ $summary['orders_count'] }}
        </div>
        <div class="card">
            {{ __('reports.earnings') }}:
            {{ number_format($summary['earnings'], 2) }}
        </div>
         <div class="card">
            {{ __('reports.currency') }}:
            {{ $currency->symbol }}
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>
                    {{ __('reports.partners') }}
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
</body>
</html>
