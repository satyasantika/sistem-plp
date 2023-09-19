@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Penilaian PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Penilaian {{ substr($form_id,-2) }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="assessment-table" role="grid">
                                    @php
                                        $form_times = App\Models\Form::find($form_id)->times;
                                    @endphp
                                    <thead>
                                        <tr role="row">
                                            <th>Mahasiswa</th>
                                            @for ($i = 0; $i < $form_times; $i++)
                                                @if (substr($form_id,-2) == 'N3')
                                                <th>Perangkat ke-{{ $i+1 }}</th>
                                                @elseif (substr($form_id,-2) == 'N5')
                                                <th>Tampil ke-{{ $i+1 }}</th>
                                                @else
                                                <th>Nilai {{ substr($form_id,-2) }}</th>
                                                @endif
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maps as $map)
                                        <tr>
                                            <td>
                                                {{ $map->students->name ?? '' }}
                                            </td>
                                            @for ($i = 0; $i < $form_times; $i++)
                                            <td>
                                                @php
                                                $assessment = App\Models\Assessment::where([
                                                    'assessor' => (auth()->user()->hasrole('guru')) ? 'guru' : 'dosen',
                                                    'plp_order' => $plp_order,
                                                    'map_id' => $map->id,
                                                    'form_id' => $form_id,
                                                    'form_order' => $i+1
                                                ]);
                                                @endphp
                                                @if ($assessment->exists())
                                                {{ $assessment->first()->grade }} &nbsp;
                                                @canany(['aktivitas/schoolassessments/plp1-update','aktivitas/schoolassessments/plp2-update'])
                                                <button type="button"
                                                    data-id={{ $assessment->first()->id }}
                                                    data-form_order="{{ $i+1 }}"
                                                    data-map_id="{{ $map->id }}"
                                                    data-jenis="edit"
                                                    class="btn btn-success btn-sm mb-2 action"
                                                >Edit</button>
                                                @endcanany
                                                @else
                                                <button
                                                    type="button"
                                                    data-form_order="{{ $i+1 }}"
                                                    data-map_id="{{ $map->id }}"
                                                    data-jenis="add"
                                                    class="btn btn-primary btn-sm mb-2 action"
                                                >Isi</button>
                                                @endif
                                            </td>
                                            @endfor
                                        </tr>
                                        @empty
                                        <div class="alert alert-info">Belum ada catatan</div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script src="{{ asset('') }}assets/js/assessment-datatables.js"></script>
    <script>
        crudDataTables('assessment-table')
    </script>
@endpush
