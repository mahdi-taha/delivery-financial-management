<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('settlements.title') }} #{{ $settlement->settlement_num }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 25px;
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
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
            text-align: end;
        }
        .section {
            margin-top: 20px;
        }
        .section-title {
            background: #eee;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            border: 1px solid #ccc;
            padding: 8px;
            width: 25%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #eee;
        }
        th,
        td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: center;
        }
        .totals {
            margin-top: 20px;
        }
        .totals td {
            padding: 10px;
        }
        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature div {
            width: 220px;
            text-align: center;
        }
        .signature hr {
            margin-bottom: 10px;
        }
        @page {
            size: A4;
            margin: 15mm;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
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
            <h2>{{ __('settlements.title') }}</h2>
            <strong>#{{ $settlement->settlement_num }}</strong>
            <br>
            {{ \Carbon\Carbon::parse($settlement->date)->format('d/m/Y') }}
        </div>
    </div>
    <div class="section">
        <div class="section-title">
            {{ __('settlements.driver_information') }}
        </div>
        <table class="info-table">
            <tr>
                <td>
                    <strong>{{ __('settlements.driver') }}</strong><br>
                    {{ $settlement->driver->name }}
                </td>
                <td>
                    <strong>{{ __('settlements.driver_percentage') }} </strong><br>
                    {{ $settlement->driver_percentage }}%
                </td>
                <td>
                    <strong>{{ __('settlements.salary') }}</strong><br>
                    {{ number_format($settlement->driver->salary, 2 ?? 0.0) }} {{ $defaultCurrency->symbol }}
                </td>
                <td>
                    <strong>{{ __('settlements.status') }}</strong><br>
                    {{ ucfirst($settlement->status) }}
                </td>
            </tr>
        </table>
    </div>
    @if ($settlement->notes)
        <div class="section">
            <div class="section-title">
                {{ __('settlements.notes') }}
            </div>
            <div style="border:1px solid #ccc;padding:10px;">
                {!! $settlement->notes !!}
            </div>
        </div>
    @endif
    <div class="section">
        <div class="section-title">
            {{ __('settlements.orders') }}
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('settlements.order') }}</th>
                    <th>{{ __('settlements.currency') }}</th>
                    <th>{{ __('settlements.partner') }}</th>
                    <th>{{ __('settlements.delivery') }}</th>
                    <th>{{ __('settlements.partner_name') }}</th>
                    <th>{{ __('settlements.driver') }}</th>
                    <th>{{ __('settlements.company') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settlement->orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_num }}</td>
                        <td>{{ $order->currency->symbol }}</td>
                        <td>{{ $order->contractCompany?->name ?? '-' }}</td>
                        <td>{{ number_format($order->delivery_fee, 2) }}</td>
                        <td>{{ number_format($order->contract_company_amount, 2) }}</td>
                        <td>{{ number_format($order->driver_amount, 2) }}</td>
                        <td>{{ number_format($order->company_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <div class="section-title">
            {{ __('settlements.settlement_totals') }}
        </div>
        <table class="totals">
            <tr>
                <td><strong>{{ __('settlements.total_orders') }}</strong></td>
                <td>{{ $settlement->total_orders }}</td>
                <td><strong>{{ __('settlements.driver_total') }}</strong></td>
                <td>{{ number_format($settlement->driver_total, 2) }} {{ $defaultCurrency->symbol }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('settlements.company_total') }}</strong></td>
                <td>{{ number_format($settlement->company_total, 2) }} {{ $defaultCurrency->symbol }}</td>
                <td><strong>{{ __('settlements.partner_total') }}</strong></td>
                <td>{{ number_format($settlement->contract_company_total, 2) }} {{ $defaultCurrency->symbol }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('settlements.subtotal') }}</strong></td>
                <td>{{ number_format($settlement->subtotal, 2) }} {{ $defaultCurrency->symbol }}</td>
                <td><strong>{{ __('settlements.grand_total') }}</strong></td>
                <td>{{ number_format($settlement->delivery_total, 2) }} {{ $defaultCurrency->symbol }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
