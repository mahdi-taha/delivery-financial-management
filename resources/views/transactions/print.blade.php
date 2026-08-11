<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('transactions.report_title') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #222;
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
        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 10px;
            flex: 1;
            text-align: center;
        }
        .card h4 {
            margin: 0 0 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f2f2f2;
        }
        .in {
            color: green;
        }
        .out {
            color: red;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
{{-- <body onload="window.print()"> --}}<body>
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
            <h2>{{ __('transactions.report_title') }}</h2>
            {{ __('transactions.from') }}:
            {{ request('date_from') ?? __('transactions.all') }}
            -
            {{ __('transactions.to') }}:
            {{ request('date_to') ?? __('transactions.all') }}
        </div>
    </div>
    <div class="summary">
        <div class="card">
            <h4>{{ __('transactions.total_in') }}</h4>
            <strong>
                {{ number_format($summary->total_in ?? 0, 2) }} {{ $currency->symbol }}
            </strong>
        </div>
        <div class="card">
            <h4>{{ __('transactions.total_out') }}</h4>
            <strong>
                {{ number_format($summary->total_out ?? 0, 2) }} {{ $currency->symbol }}
            </strong>
        </div>
        <div class="card">
            <h4>{{ __('transactions.net') }}</h4>
            <strong>
                {{ number_format($summary->net ?? 0, 2) }} {{ $currency->symbol }}
            </strong>
        </div>
        <div class="card">
            <h4>{{ __('transactions.transactions') }}</h4>
            <strong>
                {{ $summary->transactions_count ?? 0 }}
            </strong>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('transactions.date') }}</th>
                <th>{{ __('transactions.number') }}</th>
                <th>{{ __('transactions.type') }}</th>
                <th>{{ __('transactions.direction') }}</th>
                <th>{{ __('transactions.amount') }}</th>
                <th>{{ __('transactions.rate') }}</th>
                <th>{{ __('transactions.driver') }}</th>
                <th>{{ __('transactions.user') }}</th>
                <th>{{ __('transactions.notes') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}
                    </td>
                    <td>
                        {{ $transaction->transaction_num }}
                    </td>
                    <td>
                        {{ __('transactions.' . $transaction->type) }}
                    </td>
                    <td class="{{ $transaction->direction }}">
                        {{ __('transactions.' . $transaction->direction) }}
                    </td>
                    <td>
                        {{ number_format($transaction->amount, 2) }} {{ $transaction->currency->symbol }}
                    </td>
                    <td>
                        {{ rtrim(rtrim(number_format($transaction->currency->rate, 6), '0'), '.') }}
                    </td>
                    <td>
                        {{ $transaction->driver?->name ?? '-' }}
                    </td>
                    <td>
                        {{ $transaction->user?->name ?? '-' }}
                    </td>
                    <td>
                        {{ $transaction->notes }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
