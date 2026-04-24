@extends('layouts.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .progress-tabs {
            border-bottom: 1px solid #d3e0f1;
        }

        .progress-panel {
            border-radius: 14px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            overflow: hidden;
        }

        .progress-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .progress-toolbar label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
            color: #4c6281;
            margin-bottom: 6px;
            display: block;
        }

        .progress-toolbar select {
            min-width: 240px;
            border: 1px solid #c8d7eb;
            border-radius: 10px;
            background: #ffffff;
            color: #29405f;
            padding: 8px 12px;
        }

        .progress-summary-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            color: #214466;
            border: 1px solid #cfe0f2;
            font-weight: 700;
        }

        .progress-summary-badge .value {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.82);
            color: #163653;
            border: 1px solid rgba(157, 176, 207, 0.6);
        }

        .progress-data-table {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
        }

        .progress-data-table thead th {
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            color: #24364f;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            border-bottom: 1px solid #d4e0f0 !important;
            padding: 12px 14px;
        }

        .progress-data-table tbody td {
            border-bottom: 1px solid #e7edf7;
            padding: 13px 14px;
            vertical-align: middle;
            color: #2a3c57;
            background: transparent;
        }

        .progress-data-table tbody tr:hover td {
            background: #f4f9ff;
        }

        .progress-person {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 260px;
        }

        .progress-person-name {
            font-weight: 600;
            color: #23344f;
            line-height: 1.4;
        }

        .progress-statuses {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 7px;
        }

        .progress-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .progress-status-chip.is-success {
            background: linear-gradient(135deg, #ebfbf3 0%, #f1fff7 100%);
            color: #18634e;
            border-color: #c7eadc;
        }

        .progress-status-chip.is-warning {
            background: linear-gradient(135deg, #fff8e8 0%, #fffdf3 100%);
            color: #926208;
            border-color: #f0dfb0;
        }

        .progress-status-chip.is-danger {
            background: linear-gradient(135deg, #fff0f0 0%, #fff7f7 100%);
            color: #9a3341;
            border-color: #f0cad0;
        }

        .progress-subject-view[hidden] {
            display: none !important;
        }

        .progress-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 10px 10px 0 0;
            color: #355171;
            font-weight: 600;
            padding: 10px 14px;
        }

        .progress-tabs .nav-link.active {
            color: #123556;
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            border-color: #d3e0f1 #d3e0f1 transparent;
        }

        body.dark .progress-tabs {
            border-bottom-color: rgba(173, 193, 223, 0.2);
        }

        body.dark .progress-tabs .nav-link {
            color: #c8d8f0;
        }

        body.dark .progress-tabs .nav-link.active {
            color: #eef4ff;
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            border-color: rgba(173, 193, 223, 0.28) rgba(173, 193, 223, 0.28) transparent;
        }

        body.dark .progress-panel {
            border-color: rgba(173, 193, 223, 0.2);
            background: linear-gradient(180deg, #1a2639 0%, #162233 100%);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.32);
        }

        body.dark .progress-toolbar label {
            color: #b7cae7;
        }

        body.dark .progress-toolbar select {
            border-color: rgba(173, 193, 223, 0.3);
            background: #111d2d;
            color: #d7e4fa;
        }

        body.dark .progress-summary-badge {
            color: #d7e8ff;
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            border-color: rgba(173, 193, 223, 0.24);
        }

        body.dark .progress-summary-badge .value {
            background: rgba(10, 21, 35, 0.55);
            color: #dbe9ff;
            border-color: rgba(173, 193, 223, 0.28);
        }

        body.dark .progress-data-table thead th {
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            color: #e9f2ff;
            border-bottom-color: rgba(173, 193, 223, 0.2) !important;
        }

        body.dark .progress-data-table tbody td {
            color: #d4e2f8;
            border-bottom-color: rgba(173, 193, 223, 0.14);
        }

        body.dark .progress-data-table tbody tr:hover td {
            background: rgba(111, 157, 214, 0.12);
        }

        body.dark .progress-person-name {
            color: #dfebff;
        }

        body.dark .progress-status-chip.is-success {
            background: linear-gradient(135deg, rgba(33, 132, 96, 0.28) 0%, rgba(20, 112, 81, 0.2) 100%);
            color: #caf7e7;
            border-color: rgba(110, 211, 177, 0.3);
        }

        body.dark .progress-status-chip.is-warning {
            background: linear-gradient(135deg, rgba(177, 132, 24, 0.28) 0%, rgba(147, 109, 14, 0.2) 100%);
            color: #ffe8ab;
            border-color: rgba(235, 202, 113, 0.28);
        }

        body.dark .progress-status-chip.is-danger {
            background: linear-gradient(135deg, rgba(158, 63, 85, 0.28) 0%, rgba(133, 47, 68, 0.2) 100%);
            color: #ffd5dc;
            border-color: rgba(230, 138, 160, 0.28);
        }

        @media (max-width: 992px) {
            .progress-person {
                min-width: unset;
            }

            .progress-statuses {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Progress Penilaian PLP {{ $activeYear }}
    </div>
    <div class="content-wrapper">
        @if($useLegacyTabs && count($legacyTabs) > 0)
            <ul class="nav nav-tabs progress-tabs mb-3" id="progressTabs" role="tablist">
                @foreach($legacyTabs as $tab)
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

            <div class="tab-content" id="progressTabsContent">
                @foreach($legacyTabs as $tab)
                    <div
                        class="tab-pane fade {{ $activeLegacyTab === $tab['key'] ? 'show active' : '' }}"
                        id="content-{{ $tab['key'] }}"
                        role="tabpanel"
                        aria-labelledby="tab-{{ $tab['key'] }}"
                    >
                        <div class="row">
                            @includeWhen(auth()->user()->hasAnyRole('data','kajur'), 'report.assessment-result-departement', [
                                'plp_order' => $tab['plp_order'],
                                'departmentData' => $tab['departmentData'],
                            ])
                            @if($tab['plp_order'] === 2)
                                @can('plp2-read')
                                    @includeWhen(auth()->user()->hasAnyRole('kepsek','korguru','data'), 'report.assessment-result-school', [
                                        'plp_order' => 2,
                                        'schoolData' => $tab['schoolData'],
                                    ])
                                @endcan
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                @includeWhen(auth()->user()->hasAnyRole('data','kajur'), 'report.only.assessment-result-departement', [
                    'departmentData' => $departmentData,
                ])
                @can('plp2-read')
                    @includeWhen(auth()->user()->hasAnyRole('kepsek','korguru','data'), 'report.only.assessment-result-school', [
                        'schoolData' => $schoolData,
                    ])
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection

@push('js')
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-progress-subject-select').forEach(function (selectEl) {
                var wrapper = selectEl.closest('[data-progress-subject-switcher]');
                if (!wrapper) {
                    return;
                }

                var updateView = function () {
                    var selectedValue = selectEl.value;
                    wrapper.querySelectorAll('.progress-subject-view').forEach(function (view) {
                        view.hidden = view.dataset.subjectId !== selectedValue;
                    });
                };

                selectEl.addEventListener('change', updateView);
                updateView();
            });
        });
    </script>
@endpush
