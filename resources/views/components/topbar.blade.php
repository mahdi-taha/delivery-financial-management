<header class="topbar">
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
        ☰
    </button>
    @auth
        <div class="language-switcher">
            <i class="bi bi-translate"></i>
            <a href="{{ route('language', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'fw-bold' : '' }}">
                EN
            </a>
            <span>/</span>
            <a href="{{ route('language', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'fw-bold' : '' }}">
                AR
            </a>
        </div>
        <div class="topbar-user dropdown ms-0">
            <button class="user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($companyInfo?->logo)
                    <img src="{{ asset('storage/' . $companyInfo->logo) }}" alt="logo" height="40">
                @endif
                <div class="user-details">
                    <span>
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                    <small>
                        {{ auth()->user()->role?->name ?? 'No Role' }}
                    </small>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu shadow">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i>
                            {{ __('componants.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @endauth
</header>
