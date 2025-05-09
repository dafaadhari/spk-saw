<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'Dashboard SPK')</title>
    <link rel="stylesheet" href="{{ asset('tailadmin/css/styles.css') }}">
</head>
<body>
    <div class="flex h-screen overflow-hidden">
        @include('partials.sidebar') <!-- Gantikan dengan include langsung -->

        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            @include('partials.header') <!-- Gantikan dengan include langsung -->

            <main>
                <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('tailadmin/js/main.js') }}"></script>
</body>
</html>
