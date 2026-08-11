<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page }}</title>
    @if ($companyInfo?->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $companyInfo->logo) }}">
    @endif
    {{ $header ?? '' }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (app()->getLocale() == 'ar')
        @vite(['resources/css/bootstrap.rtl.css', 'resources/js/app.js', 'resources/css/sidebarrtl.css'])
    @else
        @vite(['resources/css/bootstrap.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="dashboard-wrapper">
        <x-sidebar page="{{ $page }}" />
        <div class="main-area">
            <x-topbar />
            <main class="container-fluid page-content">
                {{ $slot }}
            </main>
        </div>
    </div>
    <div class="sidebar-overlay"></div>
    <x-footer />
</body>
</html>
