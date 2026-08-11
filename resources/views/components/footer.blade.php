@if (session('success'))
    <div
        class="toast-message"
        data-type="success"
        data-message="{{ session('success') }}">
    </div>
@endif
@if (session('error'))
    <div
        class="toast-message"
        data-type="error"
        data-message="{{ session('error') }}">
    </div>
@endif
@stack('scripts')