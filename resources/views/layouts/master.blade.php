<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PLP {{ $activeYear }}</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
            integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
            crossorigin="anonymous" />
    </noscript>

    <link rel="stylesheet" href="{{ asset('') }}vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}vendor/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('') }}vendor/perfect-scrollbar/css/perfect-scrollbar.css">

    <!-- CSS for this page only -->
    @stack('css')
    <!-- End CSS  -->

    <link rel="stylesheet" href="{{ asset('') }}assets/css/style.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap-override.min.css">
    <link rel="stylesheet" id="theme-color" href="{{ asset('') }}assets/css/dark.min.css">

    <style>
        :root {
            --mc-light-bg: linear-gradient(180deg, #f7fbff 0%, #eef3fa 100%);
            --mc-light-panel: linear-gradient(165deg, #ffffff 0%, #f6f9ff 100%);
            --mc-light-border: #dde6f2;
            --mc-light-shadow: 0 10px 30px rgba(19, 38, 67, 0.08);
            --mc-light-title: #25344d;

            --mc-dark-bg: linear-gradient(180deg, #151f31 0%, #10192a 100%);
            --mc-dark-panel: linear-gradient(165deg, rgba(29, 43, 66, 0.95) 0%, rgba(21, 33, 52, 0.95) 100%);
            --mc-dark-border: rgba(166, 187, 222, 0.18);
            --mc-dark-shadow: 0 16px 34px rgba(0, 0, 0, 0.32);
            --mc-dark-title: #e7efff;
        }

        .main-content {
            background: var(--mc-light-bg);
            border-radius: 16px 16px 0 0;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
        }

        .main-content>.title {
            color: var(--mc-light-title);
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin-bottom: 10px;
        }

        .main-content .content-wrapper {
            padding: 0 2px;
        }

        .main-content .content,
        .main-content .card {
            background: var(--mc-light-panel);
            border: 1px solid var(--mc-light-border);
            border-radius: 14px;
            box-shadow: var(--mc-light-shadow);
        }

        .main-content .card .card-body {
            padding: 20px;
        }

        body.dark .main-content {
            background: var(--mc-dark-bg);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.02);
        }

        body.dark .main-content>.title {
            color: var(--mc-dark-title);
        }

        body.dark .main-content .content,
        body.dark .main-content .card {
            background: var(--mc-dark-panel);
            border: 1px solid var(--mc-dark-border);
            box-shadow: var(--mc-dark-shadow);
        }

        @media (max-width: 900px) {
            .main-content {
                border-radius: 12px 12px 0 0;
            }

            .main-content .card .card-body {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="shadow-header"></div>
        @include('layouts.header')
        @include('layouts.nav')

        @yield('content')

        @include('layouts.settings')
        @include('layouts.footer')
        <div class="overlay action-toggle">
        </div>
    </div>
    <script src="{{ asset('') }}vendor/bootstrap/dist/js/bootstrap.bundle.js" defer></script>
    <script src="{{ asset('') }}vendor/perfect-scrollbar/dist/perfect-scrollbar.min.js" defer></script>

    <!-- js for this page only -->
    @stack('js')
    <!-- ======= -->
    <script src="{{ asset('') }}assets/js/main.js" defer></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            Main.init();
        });
    </script>
</body>

</html>
