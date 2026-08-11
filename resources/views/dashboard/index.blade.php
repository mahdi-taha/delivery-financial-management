<x-main page="Dashboard">
    <x-slot name="header">
        <x-header />
    </x-slot>
    {{-- stats card --}}
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card shadow-sm border-orders">
                <div class="text-center">
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-box-seam stat-icon icon-orders"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $ordersToday }}</div>
                        <div class="text-muted">{{ __('dashboard.orders') }} <br> ({{ __('dashboard.today') }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card border-revenue shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-graph-up-arrow stat-icon icon-revenue"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($financialToday->revenue ?? 0, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.revenue') }} <br> ({{ __('dashboard.today') }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card shadow-sm border-income ">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-wallet2 stat-icon icon-income"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format(($financialToday->company_earnings ?? 0) + ($collectionToday->company_collections ?? 0), 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.company_income') }} <br> ({{ __('dashboard.today') }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card shadow-sm border-profit">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-cash-coin stat-icon icon-profit"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format(($financialToday->company_earnings ?? 0) + ($collectionToday->company_collections ?? 0) - ($expensesToday ?? 0), 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.profit') }} <br> ({{ __('dashboard.today') }})</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card border-orders shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-cart3 stat-icon icon-orders"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $ordersMonth }}</div>
                        <div class="text-muted">{{ __('dashboard.orders') }} <br> ({{ __('dashboard.month') }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card border-revenue shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-bar-chart-line stat-icon icon-revenue"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($financialMonth->revenue ?? 0, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.revenue') }} <br> ({{ __('dashboard.month') }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card shadow-sm border-income">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-cash-stack stat-icon icon-income"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format(($financialMonth->company_earnings ?? 0) + ($collectionMonth->company_collections ?? 0), 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.company_income') }} <br>
                            ({{ __('dashboard.month') }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card stat-card shadow-sm border-profit">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-piggy-bank stat-icon icon-profit"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format(($financialMonth->company_earnings ?? 0) + ($collectionMonth->company_collections ?? 0) - ($expensesMonth ?? 0), 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">{{ __('dashboard.profit') }} <br> ({{ __('dashboard.month') }})</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CASH FLOW --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body pb-5">
                    <div style="height:300px;">
                        <h6>{{ __('dashboard.cash_flow') }} ({{ $defaultCurrecy?->symbol ?? '' }})</h6>
                        <canvas id="cashFlowChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body pb-5">
                    <div style="height:300px;">
                        <h6>{{ __('dashboard.transactions_by_type') }}</h6>
                        <canvas id="transactionPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Operations Status --}}
    <div class="row mt-4">
        <!-- Pending Settlement Count -->
        <div class="col-md-3">
            <div class="card stat-card shadow pending-settlement">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-hourglass-split stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ $pendingSettlementsCount }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.pending_settlement') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Closed Settlement Count -->
        <div class="col-md-3">
            <div class="card stat-card shadow closed-settlement">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-check2-circle stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ $closedSettlementsCount }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.closed_settlement') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Collection Count -->
        <div class="col-md-3">
            <div class="card stat-card shadow pending-collection">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-clock-history stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ $pendingCollectionsCount }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.pending_collection') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Paid Collection Count -->
        <div class="col-md-3">
            <div class="card stat-card shadow paid-collection">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-cash-stack stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ $paidCollectionsCount }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.paid_collection') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <!-- Pending Settlement Amount -->
        <div class="col-md-3">
            <div class="card stat-card shadow pending-settlement">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-wallet2 stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($pendingSettlementsAmount, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.pending_settlement') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Closed Settlement Amount -->
        <div class="col-md-3">
            <div class="card stat-card shadow closed-settlement">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-wallet-fill stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($closedSettlementsAmount, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.closed_settlement') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Collection Amount -->
        <div class="col-md-3">
            <div class="card stat-card shadow pending-collection">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-cash-coin stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($pendingCollectionsAmount, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.pending_collection') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Paid Collection Amount -->
        <div class="col-md-3">
            <div class="card stat-card shadow paid-collection">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-bank stat-icon"></i>
                    </div>
                    <div>
                        <div class="stat-value">
                            {{ number_format($paidCollectionsAmount, 2) }}
                            {{ $defaultCurrecy?->symbol ?? '' }}
                        </div>
                        <div class="text-muted">
                            {{ __('dashboard.paid_collection') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h6>
                    {{ __('dashboard.revenue_trend') }} ({{ $defaultCurrecy?->symbol ?? '' }})
                    <select id="revenueFilter" class="float-end form-select" style="width: 120px; height: 40px">
                        <option value="daily">{{ __('dashboard.daily') }}</option>
                        <option value="monthly">{{ __('dashboard.monthly') }}</option>
                    </select>
                </h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h6>
                    {{ __('dashboard.profit_trend') }} ({{ $defaultCurrecy?->symbol ?? '' }})
                    <select id="profitFilter" class="float-end form-select" style="width: 120px; height: 40px">
                        <option value="daily">{{ __('dashboard.daily') }}</option>
                        <option value="monthly">{{ __('dashboard.monthly') }}</option>
                    </select>
                </h6>
                <canvas id="profitChart"></canvas>
            </div>
        </div>
    </div>
    {{-- top 5 drivers --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>
                        {{ __('dashboard.top_earning_drivers') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    @foreach ($topEarningDrivers as $index => $driver)
                        <div class="driver-item d-flex align-items-center">
                            <div class="driver-rank">
                                {{ $index + 1 }}
                            </div>
                            <div class="driver-icon me-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">
                                    {{ $driver->name }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">
                                    {{ number_format($driver->earnings, 2) }}
                                    {{ $defaultCurrecy?->symbol ?? '' }}
                                </div>
                                <small class="text-muted">
                                    {{ __('dashboard.earnings') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>
                        {{ __('dashboard.top_delivering_drivers') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    @foreach ($topDeliveringDrivers as $index => $driver)
                        <div class="driver-item d-flex align-items-center">
                            <div class="driver-rank">
                                {{ $index + 1 }}
                            </div>
                            <div class="driver-icon me-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">
                                    {{ $driver->name }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">
                                    {{ $driver->deliveries }}
                                </div>
                                <small class="text-muted">
                                    {{ __('dashboard.orders') }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h6>
                    {{ __('dashboard.orders_trend') }} ({{ $defaultCurrecy?->symbol ?? '' }})
                    <select id="ordersFilter" class="float-end form-select" style="width: 120px; height: 40px">
                        <option value="daily">{{ __('dashboard.daily') }}</option>
                        <option value="monthly">{{ __('dashboard.monthly') }}</option>
                    </select>
                </h6>
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h6>
                    {{ __('dashboard.company_earnings_trend') }} ({{ $defaultCurrecy?->symbol ?? '' }})
                    <select id="earningsFilter" class="float-end form-select" style="width: 120px; height: 40px">
                        <option value="daily">{{ __('dashboard.daily') }}</option>
                        <option value="monthly">{{ __('dashboard.monthly') }}</option>
                    </select>
                </h6>
                <canvas id="earningsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm pb-5">
                <div class="card-body">
                    <div style="height:320px;">
                        <h6 class="d-flex justify-content-between">{{ __('dashboard.settlement_distribution') }}
                            <span> {{ __('dashboard.total') }}:
                                {{ number_format($financialMonth->revenue ?? 0, 2) }}{{ $defaultCurrecy?->symbol ?? '' }}</span>
                        </h6>
                        <canvas id="settlementPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm pb-5">
                <div class="card-body">
                    <div style="height:320px;">
                        <h6 class="d-flex justify-content-between">{{ __('dashboard.collection_distribution') }}
                            <span> {{ __('dashboard.total') }}:
                                {{ number_format($collectionMonth->received_collections ?? 0, 2) }}{{ $defaultCurrecy?->symbol ?? '' }}</span>
                        </h6>
                        <canvas id="collectionPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.dashboardData = {
            settlement: {
                labels: [
                    @json(__('dashboard.company_profit')),
                    @json(__('dashboard.driver_share')),
                    @json(__('dashboard.partner_share'))
                ],
                data: [
                    {{ $financialMonth->company_earnings ?? 0 }},
                    {{ $financialMonth->driver_profit ?? 0 }},
                    {{ $financialMonth->partner_share ?? 0 }}
                ]
            },
            collection: {
                labels: [
                    @json(__('dashboard.company_collections')),
                    @json(__('dashboard.driver_collections'))
                ],
                data: [
                    {{ $collectionMonth->company_collections ?? 0 }},
                    {{ $collectionMonth->driver_collections ?? 0 }}
                ]
            },
            cashFlowChart: @json($cashFlowChart),
            tbt: @json($tbt),
            revenueDaily: @json($revenueDaily),
            revenueMonthly: @json($revenueMonthly),
            profitDaily: @json($profitDaily),
            profitMonthly: @json($profitMonthly),
            ordersDaily: @json($ordersDaily),
            ordersMonthly: @json($ordersMonthly),
            companyEarningsDaily: @json($companyEarningsDaily),
            companyEarningsMonthly: @json($companyEarningsMonthly),
            lang: {
                amount: @json(__('dashboard.amount')),
                revenue: @json(__('dashboard.revenue')),
                profit: @json(__('dashboard.profit')),
                orders: @json(__('dashboard.orders')),
                companyEarnings: @json(__('dashboard.company_earnings'))
            }
        };
    </script>
    @vite('resources/js/dashboard.js')
</x-main>
