<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
            integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
            crossorigin="anonymous" />


        <link rel="stylesheet" href="{{ asset('') }}vendor/bootstrap/dist/css/bootstrap.min.css">

        <link rel="stylesheet" href="{{ asset('') }}assets/css/style.css">
        <!-- <link rel="stylesheet" href="{{ asset('') }}vendor/themify-icons/themify-icons.css"> -->
        <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap-override.css">


    </head>

    <body>
        <section class="container h-100">
            <div class="row justify-content-sm-center h-100 align-items-center">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
                @yield('content')
                    <div class="text-center mt-5 text-muted">
                        Sistem PLP &copy; 2022 SaReDep (Satya-Redi-DepiArdian) &mdash; FKIP Universitas Siliwangi
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('') }}assets/js/login.js"></script>
    </body>
</html>
