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
        <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap-override.css">


    </head>

    <body>
        <section class="container">
            <div class="row">
                <div class="card mt-4">
                    <div class="card-header">
                        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login >></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="table-responsive">
                                        <h1>Mitra PLP 2022</h1>
                                        <table class="table small-font table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Mitra</th>
                                                    <th>Distribusi Peserta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($schools as $school)
                                                <tr>
                                                    <th>{{ $school->name }}</th>
                                                    <td>
                                                        @php
                                                            $subjects = App\Models\Map::select('subject_id', DB::raw('count(subject_id) as total'))
                                                                                        ->where('school_id',$school->id)
                                                                                        ->groupBy('subject_id')
                                                                                        ->get();
                                                        @endphp
                                                        @foreach ($subjects as $subject)
                                                        <span class="badge bg-light rounded-pill text-dark">
                                                            {{ ucwords($subject->subjects->abbreviation) }}
                                                            <span class="badge bg-primary rounded-pill">
                                                                {{ $subject->total }}
                                                            </span>
                                                        </span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                        </div>
                        <div class="mb-3">
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted">
                        Copyright &copy; 2022 &mdash; FKIP Universitas Siliwangi
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
