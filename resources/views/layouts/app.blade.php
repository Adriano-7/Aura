<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>
    <body>
        <main>
            <header>
                @yield('header')
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>