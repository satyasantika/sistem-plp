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

        <style>
            body {
                min-height: 100vh;
                background:
                    radial-gradient(1200px 700px at 0% 0%, #1b4d77 0%, transparent 60%),
                    radial-gradient(900px 540px at 100% 0%, #0c7d72 0%, transparent 56%),
                    linear-gradient(155deg, #071c2f 0%, #0a2135 58%, #092f4e 100%);
                color: #f4f8ff;
            }

            .auth-grain {
                position: fixed;
                inset: 0;
                pointer-events: none;
                opacity: 0.14;
                background-image: radial-gradient(circle at 1px 1px, #ffffff 1px, transparent 0);
                background-size: 20px 20px;
                mix-blend-mode: soft-light;
                z-index: 0;
            }

            .auth-orb {
                position: fixed;
                border-radius: 999px;
                filter: blur(4px);
                opacity: 0.35;
                pointer-events: none;
                z-index: 0;
                animation: floatOrb 9s ease-in-out infinite;
            }

            .auth-orb-a {
                width: 190px;
                height: 190px;
                top: 8%;
                right: 10%;
                background: radial-gradient(circle at 30% 30%, #ffd27d, #f4b942);
            }

            .auth-orb-b {
                width: 150px;
                height: 150px;
                bottom: 9%;
                left: 9%;
                animation-delay: 1.3s;
                background: radial-gradient(circle at 30% 30%, #35d7ca, #12b4a6);
            }

            .auth-shell {
                position: relative;
                z-index: 1;
            }

            .auth-footer-note {
                color: #c5d8ef;
                font-size: 12px;
                line-height: 1.5;
            }

            @keyframes floatOrb {
                0%,
                100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-12px);
                }
            }
        </style>


    </head>

    <body>
        <div class="auth-grain"></div>
        <div class="auth-orb auth-orb-a"></div>
        <div class="auth-orb auth-orb-b"></div>

        <section class="container h-100 auth-shell">
            <div class="row justify-content-sm-center h-100 align-items-center">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
                @yield('content')
                    <div class="text-center mt-4 auth-footer-note">
                        FKIP EDU &copy; {{ date('Y') }} - Sistem PLP FKIP Universitas Siliwangi
                    </div>
                </div>
            </div>
        </section>
        <script src="{{ asset('') }}assets/js/login.js"></script>
    </body>
</html>
