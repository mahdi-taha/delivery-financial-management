@props([
    'page' => '',
])
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo text-start">
        {{ $companyInfo->name ?? config('app.name') }}
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('dashboard') }}"
            class="sidebar-link {{ $page === 'Dashboard' ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            {{ __('componants.dashboard') }}
        </a>
        <a href="{{ route('drivers.index') }}"
            class="sidebar-link {{ str_contains($page, 'Drivers') ? 'active' : '' }}">
            <i class="bi bi-car-front-fill"></i>
            {{ __('componants.drivers') }}
        </a>
        <a href="{{ route('partners.index') }}"
            class="sidebar-link {{ str_contains($page, 'Partners') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            {{ __('componants.partners') }}
        </a>
        <a href="{{ route('collections.index') }}"
            class="sidebar-link {{ str_contains($page, 'Collections') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            {{ __('componants.collections') }}
        </a>
        <a href="{{ route('transactions.index') }}"
            class="sidebar-link {{ str_contains($page, 'Transactions') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i>
            {{ __('componants.transactions') }}
        </a>
        <a href="{{ route('settlements.index') }}"
            class="sidebar-link {{ str_contains($page, 'Settlements') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            {{ __('componants.settlements') }}
        </a>
        {{-- Reports --}}
        <div class="sidebar-dropdown">
            <a class="sidebar-link border-0 w-100 text-start d-flex justify-content-between align-items-center
                {{ str_contains($page, 'Reports') ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#reportsMenu">
                <span>
                    <i class="bi bi-bar-chart-line"></i>
                    {{ __('componants.reports') }}
                </span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="reportsMenu">
                <a href="{{ route('reports.drivers') }}" class="sidebar-link">
                    <i class="bi bi-car-front-fill"></i>
                    {{ __('componants.drivers') }}
                </a>
                <a href="{{ route('reports.partners') }}" class="sidebar-link">
                    <i class="bi bi-people-fill"></i>
                    {{ __('componants.partners') }}
                </a>
                <a href="{{ route('reports.company') }}" class="sidebar-link">
                    <i class="bi bi-building"></i>
                    {{ __('componants.company') }}
                </a>
            </div>
        </div>
        {{-- Settings --}}
        <div class="sidebar-dropdown">
            <a class="sidebar-link border-0 w-100 text-start d-flex justify-content-between align-items-center
                {{ str_contains($page, 'Settings') ? 'active' : '' }}"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#settingsMenu">
                <span>
                    <i class="bi bi-gear-fill"></i>
                    {{ __('componants.settings') }}
                </span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="settingsMenu">
                <a href="{{ route('company_info.index') }}" class="sidebar-link">
                    <i class="bi bi-info-circle-fill"></i>
                    {{ __('componants.company_info') }}
                </a>
                <a href="{{ route('currencies.index') }}" class="sidebar-link">
                    <i class="bi bi-currency-exchange"></i>
                    {{ __('componants.currencies') }}
                </a>
                <a href="{{ route('payment_methods.index') }}" class="sidebar-link">
                    <i class="bi bi-credit-card-fill"></i>
                    {{ __('componants.payment_methods') }}
                </a>
                <a href="{{ route('roles.index') }}" class="sidebar-link">
                    <i class="bi bi-shield-lock-fill"></i>
                    {{ __('componants.roles') }}
                </a>
                <a href="{{ route('users.index') }}" class="sidebar-link">
                    <i class="bi bi-person-badge-fill"></i>
                    {{ __('componants.users') }}
                </a>
                <a href="{{ route('activity-logs.index') }}" class="sidebar-link">
                    <i class="bi bi-clock-history"></i>
                    {{ __('componants.activity_logs') }}
                </a>
            </div>
        </div>
    </nav>
</aside>