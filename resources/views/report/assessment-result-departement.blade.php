@php
    $subjects = App\Models\Subject::whereNot('id','03')->get();
    if (auth()->user()->hasAnyRole('kajur')) {
        $subject_id = auth()->user()->subject_id;
        $subjects = App\Models\Subject::where('id',$subject_id)->get();
    }
    $forms = ($plp_order == 1) ? ['2022N2','2022N8'] : ['2022N2','2022N6','2022N7'];
@endphp
<div class="col-auto">
    <div class="card">
        <div class="card-header">
            <h5>Progress Penilaian DPL</h5>
        </div>
        <div class="card-body">
            @foreach ($subjects as $subject)
            @php
                $quota = App\Models\Map::where([
                                            'year'=>2022,
                                            request()->segment(3)=>1,
                                            'subject_id'=>$subject->id,
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
                                                        'assessor' => 'dosen',
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
                $times = ($plp_order == 1) ? 2 : 3;
                $percent = round($assessed/($times*$quota->count()) * 100,2)
            @endphp
            <div class="accordion mb-3" id="departement-accordion">
                <div class="accordion-item">
                    <div class="progress-wrapper">
                        <div class="progress progress-bar-small">
                            <div class="progress-bar progress-bar-small" style="width: {{ $percent.'%' }}" role="progressbar"
                                aria-valuenow="{{ $assessed }}" aria-valuemin="0" aria-valuemax="$quota">
                            </div>
                        </div>
                    </div>
                    <h2 class="accordion-header" id="heading{{ $subject->id }}">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $subject->id }}"
                            aria-expanded="false" aria-controls="collapse{{ $subject->id }}">
                            {{ $subject->name }} (Progress {{ $percent.'%' }})
                        </button>
                    </h2>
                    <div id="collapse{{ $subject->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $subject->id }}" data-bs-parent="#departement-accordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table small-font table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>DPL</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // List dosen
                                            $lectures = App\Models\User::role('dosen')
                                                                        ->where('subject_id',$subject->id)
                                                                        ->orderBy('name')
                                                                        ->get();
                                            @endphp
                                        @foreach ($lectures as $lecture)
                                        <tr>
                                            @php
                                                // dosen dalam mapping
                                                $quota = App\Models\Map::where([
                                                                            'lecture_id'=>$lecture->id,
                                                                            'year'=>2022,
                                                                            request()->segment(3)=>1,
                                                                            'subject_id'=>$subject->id,
                                                                        ])->whereNotNull('student_id')
                                                                        ->get();
                                            @endphp
                                            <td>
                                                @if (isset($map->lectures->phone))
                                                    <a href="{{ 'http://wa.me/62'.$map->lectures->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                                @endif

                                                {{ $map->lectures->name ?? '' }}
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
                                                                                            'assessor' => 'dosen',
                                                                                            'form_id' => $form,
                                                                                            'form_order' => $i
                                                                                            ]);
                                                                if ($assessment->doesntExist()) {
                                                                    continue;
                                                                }
                                                                $assessed += 1/($assessment->count() * $quota->count());
                                                            @endphp
                                                        @endforeach
                                                    @endfor
                                                    @for ($i = 1; $i <= $form_times; $i++)
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
