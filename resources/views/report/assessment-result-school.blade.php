@php
    $schools = App\Models\School::all();
    $forms = ['2022N1','2022N3','2022N4','2022N5','2022N6','2022N7'];
@endphp
<div class="col-auto">
    <div class="card">
        <div class="card-header">
            <h5>Progress Penilaian DPL</h5>
        </div>
        <div class="card-body">
            @foreach ($schools as $school)
            @php
                $quota = App\Models\Map::where([
                                            'year'=>2022,
                                            request()->segment(3)=>1,
                                            'school_id'=>$school->id,
                                        ])->whereNotNull('student_id')
                                        ->get();
                $assessed = 0;
                foreach ($quota as $map) {
                    if (App\Models\Assessment::where([
                                                        'map_id'=>$map->id,
                                                        'plp_order'=>$plp_order,
                                                        'assessor' => 'guru'
                                                    ])->exists()) {
                        $assessed += 1;
                    } else {
                        continue;
                    }
                }
                $percent = round($assessed/$quota->count() * 100,2)
            @endphp
            <div class="accordion mb-3" id="school-accordion">
                <div class="accordion-item">
                    <div class="progress-wrapper">
                        <div class="progress progress-bar-small">
                            <div class="progress-bar progress-bar-small" style="width: {{ $percent.'%' }}" role="progressbar"
                                aria-valuenow="{{ $assessed }}" aria-valuemin="0" aria-valuemax="$quota">
                            </div>
                        </div>
                    </div>
                    <h2 class="accordion-header" id="heading{{ $school->id }}">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $school->id }}"
                            aria-expanded="false" aria-controls="collapse{{ $school->id }}">
                            {{ $school->name }} (Progress {{ $percent.'%' }})
                        </button>
                    </h2>
                    <div id="collapse{{ $school->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $school->id }}" data-bs-parent="#school-accordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table small-font table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Guru Pamong</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // List guru
                                            $teachers = App\Models\SchoolUserProposal::where('school_id',$school->id)
                                                                        ->orderBy('name')
                                                                        ->get();
                                                                        // dd($teachers);
                                            @endphp
                                        @foreach ($teachers as $teacher)
                                        <tr>
                                            @php
                                                // guru dalam mapping
                                                $quota = App\Models\Map::where([
                                                                            'teacher_id'=>App\Models\User::where('username',$teacher->nip)->first()->id,
                                                                            'year'=>2022,
                                                                            request()->segment(3)=>1,
                                                                            'school_id'=>$school->id,
                                                                        ])->whereNotNull('student_id')
                                                                        ->get();
                                                $assessed = 0;
                                                $form_id = [];
                                                // untuk setiap guru dalam mapping
                                                foreach ($quota as $map) {
                                                    // cek kesudahan guru menilai form
                                                    foreach ($forms as $form) {
                                                        $assessment = App\Models\Assessment::where([
                                                                                    'map_id'=>$map->id,
                                                                                    'plp_order'=>$plp_order,
                                                                                    'assessor' => 'guru',
                                                                                    'form_id' => $form,
                                                                                    // 'form_order' => 1
                                                                                    ]);
                                                        if ($assessment->exists())
                                                        {
                                                            $form_id[$form] = 1;
                                                            $assessed += 1/count($forms);
                                                        } else {
                                                            $form_id[$form] = 0;
                                                            continue;
                                                        }
                                                    }
                                                }
                                                $percent = round($assessed/$quota->count() * 100,2)
                                            @endphp
                                            <td>
                                                @if ($map->teachers->phone)
                                                    <a href="{{ 'http://wa.me/62'.$map->teachers->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                                @endif

                                                {{ $map->teachers->name ?? '' }}
                                            </td>
                                            <td class="text-end">
                                                @foreach ($forms as $form)
                                                    {{-- @foreach ($form_ids as $form_id) --}}
                                                        @if ($form_id[$form] == 1)
                                                        <span class="badge bg-primary rounded-pill"><i class="ti-check"></i> {{ substr($form,-2) }}</span>
                                                        @else
                                                        <span class="badge bg-danger rounded-pill"><i class="ti-close"></i> {{ substr($form,-2) }}</span>
                                                        @endif
                                                    {{-- @endforeach --}}
                                                @endforeach
                                                {{ $percent.'%' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
