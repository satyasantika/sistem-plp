@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        {{ Str::ucFirst(request()->segment(2)) }}
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Progress Pengusulan GP</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                @php
                                    $schools = App\Models\School::all();
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        <th class="text-end">Name</th>
                                        <th class="text-end">NIP</th>
                                        <th class="text-end">noWA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schools as $school)
                                    <tr>
                                        <th>{{ $school->name }}</th>
                                        @php
                                            $all = App\Models\SchoolUserProposal::where('school_id',$school->id)->count();
                                            $done_name = App\Models\SchoolUserProposal::where('school_id',$school->id)->whereNotNull('name')->count();
                                        @endphp
                                        <td
                                            @class([
                                                'text-end',
                                                ($done_name != $all) ? 'text-danger' : '',
                                            ])
                                            >{{ $done_name.'/'.$all }}</td>
                                        @php
                                            $done_nip = App\Models\SchoolUserProposal::where('school_id',$school->id)->whereNotNull('nip')->count();
                                        @endphp
                                        <td
                                            @class([
                                                'text-end',
                                                ($done_nip != $all) ? 'text-danger' : '',
                                            ])
                                            >{{ $done_nip.'/'.$all }}</td>
                                        @php
                                            $done_wa = App\Models\SchoolUserProposal::where('school_id',$school->id)->whereNotNull('phone')->count();
                                        @endphp
                                        <td
                                            @class([
                                                'text-end',
                                                ($done_wa != $all) ? 'text-danger' : '',
                                            ])
                                            >{{ $done_wa.'/'.$all }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
