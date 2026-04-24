@extends('layouts.master')

@include('report.partials.yudisium-datatable-assets')

@section('content')
<div class="main-content">
    <div class="title">
        Yudisium PLP {{ $activeYear }}
    </div>

    @if($useLegacyTabs && count($legacyTabs) > 0)
        <ul class="nav nav-tabs yudisium-tabs mb-3" id="yudisiumTabs" role="tablist">
            @foreach($legacyTabs as $index => $tab)
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link {{ $activeLegacyTab === $tab['key'] ? 'active' : '' }}"
                        id="tab-{{ $tab['key'] }}"
                        data-bs-toggle="tab"
                        data-bs-target="#content-{{ $tab['key'] }}"
                        type="button"
                        role="tab"
                        aria-controls="content-{{ $tab['key'] }}"
                        aria-selected="{{ $activeLegacyTab === $tab['key'] ? 'true' : 'false' }}"
                    >
                        {{ $tab['label'] }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="yudisiumTabsContent">
            @foreach($legacyTabs as $index => $tab)
                <div
                    class="tab-pane fade {{ $activeLegacyTab === $tab['key'] ? 'show active' : '' }}"
                    id="content-{{ $tab['key'] }}"
                    role="tabpanel"
                    aria-labelledby="tab-{{ $tab['key'] }}"
                >
                    @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat') && !empty($tab['dekanatSummary']), 'report.yudisium-dekanat', [
                        'plp_order' => $tab['plp_order'],
                        'dekanatSummary' => $tab['dekanatSummary'],
                        'tableId' => 'yudisium-dekanat-table-' . $tab['key'],
                    ])

                    @includeWhen(auth()->user()->hasRole('kajur'), 'report.yudisium-jurusan', [
                        'plp_order' => $tab['plp_order'],
                        'jurusanRows' => $tab['jurusanRows'],
                        'lectureForms' => $tab['lectureForms'],
                        'teacherForms' => $tab['teacherForms'],
                        'tableId' => 'yudisium-jurusan-table-' . $tab['key'],
                    ])
                </div>
            @endforeach
        </div>
    @else
        @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat') && !empty($dekanatSummary), 'report.only.yudisium-dekanat', [
            'dekanatSummary' => $dekanatSummary,
            'tableId' => 'yudisium-only-dekanat-table',
        ])
        @includeWhen(auth()->user()->hasRole('kajur'), 'report.only.yudisium-jurusan', [
            'jurusanRows' => $jurusanRows,
            'lectureForms' => $lectureForms,
            'teacherForms' => $teacherForms,
            'tableId' => 'yudisium-only-jurusan-table',
        ])
    @endif
</div>
@endsection
