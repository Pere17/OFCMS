<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} @yield('title') </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @include('layouts.partials.sidebar')

        <div class="flex-grow-1" style="min-width: 0;">
            @include('layouts.partials.topbar')

            <main class="ofcms-content">
                @include('layouts.partials.flash')

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
