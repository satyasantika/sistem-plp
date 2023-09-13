
@php
    $schools = App\Models\School::all();
    if (auth()->user()->hasAnyRole('kepsek','korguru')) {
        $id = auth()->user()->id;
        $schools = App\Models\School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->get();
    }
    $forms = ['2023N1','2023N3','2023N4','2023N5','2023N6','2023N7'];
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
                                            'year'=>2023,
                                            request()->segment(3)=>1,
                                            'school_id'=>$school->id,
                                        ])->whereNotNull('student_id')
                                        ->get();
                $assessed = 0;
                foreach ($quota as $map) {
                    foreach ($forms as $form){
                        $form_times = App\Models\Form::find($form)->times;
                        for ($i = 1; $i <= $form_times; $i++){
                            $assessment = App\Models\Assessment::where([
                                                        'map_id'=>$map->id,
                                                        'plp_order'=>$plp_order,
                                                        'assessor' => 'guru',
                                                        'form_id' => $form,
                                                        'form_order' => $i
                                                        ]);
                            if ($assessment->doesntExist()) {
                                continue;
                            }
                            $assessed += 1/($assessment->count());
                        }
                    }
                }
                $percent = round($assessed/(13*$quota->count()) * 100,2)
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
                    <div id="collapse{{ $school->id }}" class="accordion-collapse collapse {{ auth()->user()->hasAnyRole('kepsek','korguru') ? 'show' : '' }}"
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
                                            $school_maps = App\Models\Map::distinct()
                                                                        ->where('school_id',$school->id)
                                                                        ->pluck('teacher_id');
                                        @endphp
                                        @foreach ($school_maps as $teacher)
                                        <tr>
                                            @php
                                                // guru dalam mapping
                                                $quota = App\Models\Map::where([
                                                                            'teacher_id'=>$teacher,
                                                                            'year'=>2023,
                                                                            request()->segment(3)=>1,
                                                                            'school_id'=>$school->id,
                                                                        ])->whereNotNull('student_id')
                                                                        ->get();
                                                $user = App\Models\User::find($teacher);
                                            @endphp
                                            <td>
                                                @if (isset($user->phone))
                                                    <a href="{{ 'http://wa.me/62'.$user->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                                @endif

                                                {{ $user->name ?? '' }}
                                            </td>
                                            <td class="text-end">
                                                @foreach ($forms as $form)
                                                    @php
                                                        $form_times = App\Models\Form::find($form)->times;
                                                    @endphp
                                                    @for ($i = 1; $i <= $form_times; $i++)
                                                        @php $assessed = 0; @endphp
                                                        @foreach ($quota as $map)
                                                            @php
                                                                $assessment = App\Models\Assessment::where([
                                                                                            'map_id'=>$map->id,
                                                                                            'plp_order'=>$plp_order,
                                                                                            'assessor' => 'guru',
                                                                                            'form_id' => $form,
                                                                                            'form_order' => $i
                                                                                            ]);
                                                                if ($assessment->doesntExist()) {
                                                                    continue;
                                                                }
                                                                $assessed += 1/($assessment->count() * $quota->count());
                                                            @endphp
                                                        @endforeach
                                                        @php $form_name  = ($form_times == 1) ? substr($form,-2) : substr($form,-2).'.'.$i ;
                                                        @endphp
                                                        @if ($assessed == 1)
                                                            <span class="badge bg-success rounded-pill"><i class="ti-check"></i> {{ $form_name }}</span>
                                                        @elseif ($assessed > 0)
                                                            <span class="badge bg-warning rounded-pill"><i class="ti-reload"></i> {{ $form_name }}</span>
                                                        @else
                                                            <span class="badge bg-danger rounded-pill"><i class="ti-close"></i> {{ $form_name }}</span>
                                                        @endif
                                                    @endfor
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
            </div>
            @endforeach
        </div>
    </div>
</div>
