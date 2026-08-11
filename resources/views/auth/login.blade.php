<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
@if ($info?->logo)
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $info->logo) }}">
@endif
      @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">
                            Login
                        </h3>
                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    Username
                                </label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                                    autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Password
                                </label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            @if ($errors->any())
                                <div class="text-danger mb-2">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <button class="btn btn-main w-100">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
