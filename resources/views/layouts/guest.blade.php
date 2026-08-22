<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-check fs-1" style="color: var(--ofcms-primary);"></i>
                    <h5 class="fw-semibold mt-2 mb-0">{{ config('app.name') }}</h5>
                </div>

                @include('layouts.partials.flash')

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
