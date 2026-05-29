@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <style>
        .yudisium-table-wrap,
        .yudisium-datatable-shell {
            border-radius: 14px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            overflow: hidden;
        }

        .yudisium-datatable-shell {
            overflow-x: auto;
        }

        .yudisium-datatable-shell .dataTables_wrapper {
            width: 100%;
        }

        .yudisium-datatable-shell .yudisium-dt-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px 8px;
            border-bottom: 1px solid #e7edf7;
        }

        .yudisium-datatable-shell .dataTables_length,
        .yudisium-datatable-shell .dataTables_filter {
            display: block !important;
            visibility: visible !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            color: #4a5f7e !important;
        }

        .yudisium-datatable-shell .dataTables_length label,
        .yudisium-datatable-shell .dataTables_filter label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            font-weight: 600;
            font-size: 0.82rem;
            color: #4a5f7e;
        }

        .yudisium-datatable-shell .dataTables_filter {
            text-align: right;
        }

        .yudisium-datatable-shell .dataTables_filter input {
            min-width: 200px;
        }

        .yudisium-datatable-shell .yudisium-dt-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px 12px;
            border-top: 1px solid #e7edf7;
        }

        .yudisium-datatable-shell .dataTables_info,
        .yudisium-datatable-shell .dataTables_paginate {
            display: block !important;
            visibility: visible !important;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            color: #4a5f7e !important;
        }

        .yudisium-datatable-shell .dataTables_paginate {
            text-align: right;
        }

        .yudisium-table {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
        }

        .yudisium-table thead th {
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            color: #24364f;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            border-bottom: 1px solid #d4e0f0 !important;
            padding: 12px 14px;
        }

        .yudisium-table.dataTable thead th.sorting,
        .yudisium-table.dataTable thead th.sorting_asc,
        .yudisium-table.dataTable thead th.sorting_desc {
            background-image: none !important;
            position: relative;
            padding-right: 26px;
        }

        .yudisium-table.dataTable thead th.sorting:after,
        .yudisium-table.dataTable thead th.sorting_asc:after,
        .yudisium-table.dataTable thead th.sorting_desc:after {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            line-height: 1;
            opacity: 0.55;
        }

        .yudisium-table.dataTable thead th.sorting:after {
            content: '↕';
        }

        .yudisium-table.dataTable thead th.sorting_asc:after {
            content: '▲';
            opacity: 0.8;
        }

        .yudisium-table.dataTable thead th.sorting_desc:after {
            content: '▼';
            opacity: 0.8;
        }

        .yudisium-table tbody td,
        .yudisium-table tbody th {
            border-bottom: 1px solid #e7edf7;
            padding: 13px 14px;
            vertical-align: middle;
            color: #2a3c57;
            background: transparent;
            transition: background-color .2s ease;
        }

        .yudisium-table tbody tr:hover td,
        .yudisium-table tbody tr:hover th {
            background: #f4f9ff;
        }

        .yudisium-table-wrap .dataTables_length,
        .yudisium-table-wrap .dataTables_filter,
        .yudisium-table-wrap .dataTables_info,
        .yudisium-table-wrap .dataTables_paginate {
            padding: 10px 14px;
            color: #4a5f7e !important;
        }

        .yudisium-table-wrap .dataTables_filter input,
        .yudisium-table-wrap .dataTables_length select,
        .yudisium-datatable-shell .dataTables_filter input,
        .yudisium-datatable-shell .dataTables_length select {
            border: 1px solid #c8d7eb;
            border-radius: 10px;
            background: #ffffff;
            color: #29405f;
            padding: 6px 10px;
        }

        .yudisium-datatable-shell .dataTables_paginate .paginate_button,
        .yudisium-table-wrap .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid transparent !important;
        }

        .yudisium-datatable-shell .dataTables_paginate .paginate_button.current,
        .yudisium-table-wrap .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #1bb5a8, #57d0c5) !important;
            color: #082236 !important;
            border-color: #16a094 !important;
        }

        .yudisium-table tfoot th {
            background: #f0f6ff;
        }

        .yudisium-tabs {
            border-bottom: 1px solid #d3e0f1;
        }

        .yudisium-tabs .nav-link {
            border: 1px solid transparent;
            border-radius: 10px 10px 0 0;
            color: #355171;
            font-weight: 600;
            padding: 10px 14px;
        }

        .yudisium-tabs .nav-link.active {
            color: #123556;
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            border-color: #d3e0f1 #d3e0f1 transparent;
        }

        .yudisium-notes {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            align-items: center;
        }

        .yudisium-notes.break-line {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #d4e2f4;
        }

        .yudisium-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
        }

        .yudisium-chip .chip-value {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 20px;
            border-radius: 999px;
            padding: 0 7px;
            background: rgba(255, 255, 255, 0.8);
            color: #213650;
            border: 1px solid rgba(157, 176, 207, 0.6);
            font-size: 11px;
            font-weight: 700;
        }

        .yudisium-chip.is-lecture {
            background: linear-gradient(135deg, #eef3ff 0%, #e9f6ff 100%);
            color: #244f7b;
            border-color: #cfe1f8;
        }

        .yudisium-chip.is-teacher {
            background: linear-gradient(135deg, #ecfbf7 0%, #e9fff7 100%);
            color: #1d6b5f;
            border-color: #c8ece4;
        }

        .yudisium-chip.is-form-complete-summary,
        .yudisium-chip.is-form-all-done {
            background: linear-gradient(135deg, #eef3ff 0%, #e8f6f4 100%);
            color: #3d5573;
            border-color: #c5d6eb;
            font-size: 11px;
        }

        .yudisium-recap-skeleton {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px dashed #d4e2f4;
            background: rgba(248, 251, 255, 0.8);
        }

        .yudisium-chip.is-form-empty {
            background: linear-gradient(135deg, #fff1f0 0%, #ffe8e6 100%);
            color: #9b2c2c;
            border-color: #f5b5b0;
        }

        .yudisium-chip.is-form-empty .chip-value--empty {
            background: rgba(255, 255, 255, 0.65);
            color: #b54747;
            border-color: #efb8b3;
            font-weight: 700;
        }

        .yudisium-chip.is-form-partial {
            background: linear-gradient(135deg, #fff8e6 0%, #fff3cd 100%);
            color: #8a6d1d;
            border-color: #f0d78c;
        }

        .yudisium-chip.is-form-partial .chip-value {
            background: rgba(255, 255, 255, 0.75);
            color: #7a5c10;
            border-color: #e8c96a;
        }

        .yudisium-form-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            margin-bottom: 10px;
            font-size: 0.72rem;
            color: #6a7f9e;
        }

        .yudisium-form-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .yudisium-form-legend-swatch {
            width: 12px;
            height: 12px;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .yudisium-form-legend-swatch.is-empty {
            background: #ffe8e6;
            border-color: #f5b5b0;
        }

        .yudisium-form-legend-swatch.is-partial {
            background: #fff3cd;
            border-color: #f0d78c;
        }

        .yudisium-form-legend-swatch.is-filled-lecture {
            background: #e9f6ff;
            border-color: #cfe1f8;
        }

        .yudisium-form-legend-swatch.is-filled-teacher {
            background: #e9fff7;
            border-color: #c8ece4;
        }

        .yudisium-jurusan-recap {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #d4e2f4;
            background: linear-gradient(155deg, #f8fbff 0%, #f4faf8 100%);
        }

        .yudisium-recap-heading {
            margin: 0 0 12px;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            color: #3d5573;
        }

        .yudisium-recap-subheading {
            margin: 0 0 10px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #4c6281;
        }

        .yudisium-recap-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .yudisium-recap-hint {
            font-size: 0.75rem;
        }

        .yudisium-recap-stat {
            flex: 1 1 140px;
            display: block;
            width: auto;
            min-width: 120px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #d4e2f4;
            background: #fff;
            text-align: left;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
        }

        .yudisium-recap-stat:hover {
            border-color: #7eb0e8;
            box-shadow: 0 4px 12px rgba(36, 79, 123, 0.1);
        }

        .yudisium-recap-stat.is-active {
            border-color: #1a6fb5;
            box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.25);
            transform: translateY(-1px);
        }

        .yudisium-recap-stat.is-active .yudisium-recap-stat-value {
            color: #0d4f8c;
        }

        .yudisium-recap-stat-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6a7f9e;
            margin-bottom: 4px;
        }

        .yudisium-recap-stat-value {
            font-size: 1.05rem;
            font-weight: 800;
            color: #1a3352;
        }

        .yudisium-recap-stat-value small {
            font-size: 0.72rem;
            font-weight: 600;
            opacity: 0.85;
        }

        .yudisium-recap-stat.is-success { border-color: #b5e6d0; background: #f0faf5; }
        .yudisium-recap-stat.is-warning { border-color: #edd9a8; background: #fff9eb; }
        .yudisium-recap-stat.is-muted { border-color: #d8e0ec; background: #f6f8fb; }
        .yudisium-recap-stat.is-pass { border-color: #a8d4f0; background: #edf6ff; }
        .yudisium-recap-stat.is-fail { border-color: #f0c5cd; background: #fff5f7; }

        .yudisium-recap-letters-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .yudisium-recap-letter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(108px, 1fr));
            gap: 10px;
        }

        .yudisium-recap-letter-item {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #d4e2f4;
            background: #fff;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
        }

        .yudisium-recap-letter-item:hover {
            border-color: #7eb0e8;
            box-shadow: 0 4px 12px rgba(36, 79, 123, 0.12);
        }

        .yudisium-recap-letter-item.is-active {
            border-color: #1a6fb5;
            box-shadow: 0 0 0 2px rgba(26, 111, 181, 0.25);
            transform: translateY(-1px);
        }

        .yudisium-recap-letter-item.is-active .yudisium-recap-letter {
            color: #0d4f8c;
        }

        .yudisium-recap-letter-item.is-high {
            border-color: #b8d4f5;
            background: linear-gradient(155deg, #f5f9ff 0%, #eef8ff 100%);
        }

        .yudisium-recap-letter-item.is-low {
            border-color: #f0cad0;
            background: linear-gradient(155deg, #fff8f9 0%, #fff0f2 100%);
        }

        .yudisium-recap-letter {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a3352;
        }

        .yudisium-recap-letter-count {
            display: block;
            font-size: 0.7rem;
            color: #6a7f9e;
        }

        .yudisium-recap-letter-pct {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
            color: #244f7b;
            margin: 4px 0 6px;
        }

        .yudisium-recap-bar {
            height: 4px;
            border-radius: 999px;
            background: rgba(157, 176, 207, 0.35);
            overflow: hidden;
        }

        .yudisium-recap-bar-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #4a90d9 0%, #2eaf9a 100%);
        }

        .yudisium-recap-letter-item.is-low .yudisium-recap-bar-fill {
            background: linear-gradient(90deg, #d97a8a 0%, #c45c6e 100%);
        }

        .yudisium-recap-scale-toggle {
            font-size: 0.8rem;
            font-weight: 700;
            color: #3d5573;
            cursor: pointer;
        }

        .yudisium-recap-scale-table {
            font-size: 0.8rem;
        }

        .yudisium-table-filters {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d4e2f4;
            background: #fff;
        }

        .yudisium-filter-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6a7f9e;
            margin-bottom: 4px;
        }

        .yudisium-filter-result {
            white-space: nowrap;
        }

        .yudisium-table-panel.is-hidden {
            display: none;
        }

        .yudisium-table-deferred-hint {
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px dashed #d4e2f4;
            background: rgba(248, 251, 255, 0.8);
        }

        .yudisium-grade-cell {
            min-width: 108px;
            vertical-align: middle !important;
        }

        .yudisium-final-grade {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            line-height: 1.2;
        }

        .yudisium-grade-number {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1a3352;
            font-variant-numeric: tabular-nums;
        }

        .yudisium-grade-number--draft {
            font-size: 1rem;
            font-weight: 700;
            opacity: 0.85;
        }

        .yudisium-grade-letter {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .yudisium-grade-pass {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.65);
        }

        .yudisium-final-grade.is-primary {
            background: linear-gradient(155deg, #edf4ff 0%, #e3f0ff 100%);
            border-color: #b8d4f5;
            color: #1a4a7a;
        }

        .yudisium-final-grade.is-success {
            background: linear-gradient(155deg, #ebfaf3 0%, #e2f8ef 100%);
            border-color: #b5e6d0;
            color: #16634a;
        }

        .yudisium-final-grade.is-danger {
            background: linear-gradient(155deg, #fff0f2 0%, #ffe8ec 100%);
            border-color: #f0c5cd;
            color: #8f2d3e;
        }

        .yudisium-final-grade.is-partial {
            background: linear-gradient(155deg, #fff9eb 0%, #fff4dc 100%);
            border-color: #edd9a8;
            color: #7a5a12;
        }

        .yudisium-grade-hint {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .yudisium-grade-empty {
            display: inline-block;
            color: #8a9bb3;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .yudisium-chip.is-actor {
            font-weight: 700;
            letter-spacing: .2px;
        }

        body.dark .yudisium-table-wrap,
        body.dark .yudisium-datatable-shell {
            border-color: rgba(173, 193, 223, 0.2);
            background: linear-gradient(180deg, #1a2639 0%, #162233 100%);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.32);
        }

        body.dark .yudisium-datatable-shell .yudisium-dt-header,
        body.dark .yudisium-datatable-shell .yudisium-dt-footer {
            border-color: rgba(173, 193, 223, 0.14);
        }

        body.dark .yudisium-datatable-shell .dataTables_length label,
        body.dark .yudisium-datatable-shell .dataTables_filter label,
        body.dark .yudisium-datatable-shell .dataTables_info,
        body.dark .yudisium-datatable-shell .dataTables_paginate {
            color: #b8c9e4 !important;
        }

        body.dark .yudisium-table thead th {
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            color: #e9f2ff;
            border-bottom-color: rgba(173, 193, 223, 0.2) !important;
        }

        body.dark .yudisium-table.dataTable thead th.sorting:after,
        body.dark .yudisium-table.dataTable thead th.sorting_asc:after,
        body.dark .yudisium-table.dataTable thead th.sorting_desc:after {
            color: #d6e6ff;
            opacity: 0.7;
        }

        body.dark .yudisium-table tbody td,
        body.dark .yudisium-table tbody th {
            color: #d4e2f8;
            border-bottom-color: rgba(173, 193, 223, 0.14);
        }

        body.dark .yudisium-table tbody tr:hover td,
        body.dark .yudisium-table tbody tr:hover th {
            background: rgba(111, 157, 214, 0.12);
        }

        body.dark .yudisium-table-wrap .dataTables_length,
        body.dark .yudisium-table-wrap .dataTables_filter,
        body.dark .yudisium-table-wrap .dataTables_info,
        body.dark .yudisium-table-wrap .dataTables_paginate {
            color: #b8c9e4 !important;
        }

        body.dark .yudisium-table-wrap .dataTables_filter input,
        body.dark .yudisium-table-wrap .dataTables_length select,
        body.dark .yudisium-datatable-shell .dataTables_filter input,
        body.dark .yudisium-datatable-shell .dataTables_length select {
            border-color: rgba(173, 193, 223, 0.3);
            background: #111d2d;
            color: #d7e4fa;
        }

        body.dark .yudisium-table tfoot th {
            background: rgba(101, 130, 174, 0.15);
        }

        body.dark .yudisium-tabs {
            border-bottom-color: rgba(173, 193, 223, 0.2);
        }

        body.dark .yudisium-tabs .nav-link {
            color: #c8d8f0;
        }

        body.dark .yudisium-tabs .nav-link.active {
            color: #eef4ff;
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            border-color: rgba(173, 193, 223, 0.28) rgba(173, 193, 223, 0.28) transparent;
        }

        body.dark .yudisium-table-filters {
            border-color: rgba(173, 193, 223, 0.22);
            background: rgba(17, 29, 45, 0.55);
        }

        body.dark .yudisium-filter-label {
            color: #b7cae7;
        }

        body.dark .yudisium-jurusan-recap {
            border-color: rgba(173, 193, 223, 0.2);
            background: linear-gradient(155deg, #1e2d42 0%, #1a2838 100%);
        }

        body.dark .yudisium-recap-heading,
        body.dark .yudisium-recap-subheading,
        body.dark .yudisium-recap-scale-toggle {
            color: #c8d8f0;
        }

        body.dark .yudisium-recap-stat {
            border-color: rgba(173, 193, 223, 0.22);
            background: rgba(17, 29, 45, 0.6);
        }

        body.dark .yudisium-recap-stat-value {
            color: #e8f2ff;
        }

        body.dark .yudisium-recap-letter-item {
            border-color: rgba(173, 193, 223, 0.22);
            background: rgba(17, 29, 45, 0.55);
        }

        body.dark .yudisium-recap-letter,
        body.dark .yudisium-recap-letter-pct {
            color: #d4e8ff;
        }

        body.dark .yudisium-grade-number {
            color: #e8f2ff;
        }

        body.dark .yudisium-grade-empty {
            color: #7f94b3;
        }

        body.dark .yudisium-final-grade.is-primary {
            background: linear-gradient(155deg, rgba(52, 98, 158, 0.35) 0%, rgba(40, 78, 128, 0.28) 100%);
            border-color: rgba(120, 170, 230, 0.35);
            color: #d4e8ff;
        }

        body.dark .yudisium-final-grade.is-success {
            background: linear-gradient(155deg, rgba(33, 132, 96, 0.32) 0%, rgba(24, 112, 81, 0.25) 100%);
            border-color: rgba(96, 214, 177, 0.3);
            color: #caf7e7;
        }

        body.dark .yudisium-final-grade.is-danger {
            background: linear-gradient(155deg, rgba(158, 63, 85, 0.32) 0%, rgba(133, 47, 68, 0.25) 100%);
            border-color: rgba(230, 138, 160, 0.28);
            color: #ffd5dc;
        }

        body.dark .yudisium-final-grade.is-partial {
            background: linear-gradient(155deg, rgba(177, 132, 24, 0.28) 0%, rgba(147, 109, 14, 0.22) 100%);
            border-color: rgba(235, 202, 113, 0.28);
            color: #ffe8ab;
        }

        body.dark .yudisium-notes.break-line {
            border-top-color: rgba(173, 193, 223, 0.2);
        }

        body.dark .yudisium-chip {
            border-color: rgba(173, 193, 223, 0.3);
        }

        body.dark .yudisium-chip .chip-value {
            background: rgba(10, 21, 35, 0.55);
            color: #dbe9ff;
            border-color: rgba(173, 193, 223, 0.28);
        }

        body.dark .yudisium-chip.is-lecture {
            background: linear-gradient(135deg, rgba(88, 123, 199, 0.24) 0%, rgba(49, 109, 164, 0.2) 100%);
            color: #d3e6ff;
            border-color: rgba(136, 168, 229, 0.36);
        }

        body.dark .yudisium-chip.is-teacher {
            background: linear-gradient(135deg, rgba(40, 156, 134, 0.23) 0%, rgba(31, 136, 117, 0.2) 100%);
            color: #c7f7ed;
            border-color: rgba(96, 214, 190, 0.34);
        }

        body.dark .yudisium-chip.is-form-empty {
            background: linear-gradient(135deg, rgba(180, 70, 70, 0.28) 0%, rgba(140, 45, 45, 0.22) 100%);
            color: #ffc9c9;
            border-color: rgba(220, 120, 120, 0.4);
        }

        body.dark .yudisium-chip.is-form-partial {
            background: linear-gradient(135deg, rgba(180, 140, 40, 0.28) 0%, rgba(140, 105, 25, 0.22) 100%);
            color: #ffe9a8;
            border-color: rgba(220, 180, 80, 0.4);
        }

        body.dark .yudisium-form-legend {
            color: #9eb3d2;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script>
        (function () {
            window.yudisiumJurusanFilterRegistry = window.yudisiumJurusanFilterRegistry || {};

            function installJurusanFilterSearch($) {
                if (window.yudisiumJurusanFilterSearchInstalled) {
                    return;
                }

                window.yudisiumJurusanFilterSearchInstalled = true;

                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var tableId = settings.sTableId || (settings.nTable && settings.nTable.id) || '';
                    var cfg = window.yudisiumJurusanFilterRegistry[tableId];

                    if (!cfg) {
                        return true;
                    }

                    var api = new $.fn.dataTable.Api(settings);
                    var node = api.row(dataIndex).node();

                    if (!node) {
                        return true;
                    }

                    var passVal = cfg.getPass();
                    var letterVal = cfg.getLetter();
                    var rowPass = node.getAttribute('data-yudisium-pass') || '';
                    var rowLetter = node.getAttribute('data-yudisium-letter') || '';
                    var rowDisplayState = node.getAttribute('data-yudisium-display-state') || '';

                    if (passVal !== 'all' && !passFilterMatches(passVal, rowPass, rowDisplayState)) {
                        return false;
                    }

                    if (letterVal !== 'all' && rowLetter !== letterVal) {
                        return false;
                    }

                    return true;
                });
            }

            function passFilterMatches(passVal, rowPass, rowDisplayState) {
                if (passVal === 'sudah-dinilai') {
                    return rowDisplayState === 'complete';
                }

                if (passVal === 'belum-lengkap') {
                    return rowDisplayState === 'partial' || rowPass === 'belum-lengkap';
                }

                if (passVal === 'belum-dinilai') {
                    return rowDisplayState === 'empty' || rowPass === 'belum-dinilai';
                }

                return rowPass === passVal;
            }

            function ensureFilterState(tableId) {
                if (!window.yudisiumJurusanFilterRegistry[tableId]) {
                    window.yudisiumJurusanFilterRegistry[tableId] = {
                        pass: 'all',
                        letter: 'all',
                        getPass: function () {
                            return this.pass;
                        },
                        getLetter: function () {
                            return this.letter;
                        },
                    };
                }

                return window.yudisiumJurusanFilterRegistry[tableId];
            }

            function setTableFilters(tableId, pass, letter) {
                var state = ensureFilterState(tableId);
                state.pass = pass || 'all';
                state.letter = letter || 'all';
            }

            function findRecapForPanel($panel) {
                var tableId = $panel.find('.js-yudisium-jurusan-table').attr('id');

                if (!tableId) {
                    return $();
                }

                return $('[data-yudisium-recap][data-yudisium-table-id="' + tableId + '"]');
            }

            function clearRecapLetterFilter($panel) {
                var $recap = findRecapForPanel($panel);

                $recap.find('.js-yudisium-recap-letter-filter').removeClass('is-active').attr('aria-pressed', 'false');
                $recap.find('.js-yudisium-recap-letter-clear').prop('hidden', true);
            }

            function clearRecapStatFilter($panel) {
                var $recap = findRecapForPanel($panel);

                $recap.find('.js-yudisium-recap-stat-filter').removeClass('is-active').attr('aria-pressed', 'false');
            }

            function clearRecapFilters($panel) {
                clearRecapLetterFilter($panel);
                clearRecapStatFilter($panel);
            }

            function syncRecapStatFromSelect($panel, passVal) {
                var $recap = findRecapForPanel($panel);

                $recap.find('.js-yudisium-recap-stat-filter').each(function () {
                    var isActive = passVal !== 'all' && $(this).data('pass') === passVal;
                    $(this).toggleClass('is-active', isActive).attr('aria-pressed', isActive ? 'true' : 'false');
                });
            }

            function redrawYudisiumTable($table) {
                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().draw();
                }
            }

            function findTablePanelForRecap($recap) {
                var tableId = $recap.data('yudisium-table-id');

                if (!tableId) {
                    return $();
                }

                var $table = $('#' + tableId);

                if ($table.length) {
                    return $table.closest('[data-yudisium-table-panel]');
                }

                return $('[data-yudisium-table-panel][data-yudisium-table-id="' + tableId + '"]');
            }

            function loadDeferredTable($, $panel) {
                if (!$panel.length || $panel.data('yudisiumTableLoaded')) {
                    return Promise.resolve();
                }

                var url = $panel.attr('data-yudisium-table-url');
                if (!url) {
                    return Promise.resolve();
                }

                if ($panel.data('yudisiumTableLoading')) {
                    return $panel.data('yudisiumTableLoading');
                }

                var request = fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'text/html',
                    },
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal memuat daftar mahasiswa.');
                        }
                        return response.text();
                    })
                    .then(function (html) {
                        $panel.html(html);
                        $panel.data('yudisiumTableLoaded', true);
                        delete $panel[0].dataset.yudisiumTableUrl;
                    })
                    .catch(function () {
                        $panel.html('<div class="alert alert-warning mb-0 py-2 small">Daftar mahasiswa gagal dimuat.</div>');
                    })
                    .finally(function () {
                        $panel.removeData('yudisiumTableLoading');
                    });

                $panel.data('yudisiumTableLoading', request);

                return request;
            }

            function initTableInPanel($, $panel) {
                var $table = $panel.find('.js-yudisium-jurusan-table').first();

                if (!$table.length) {
                    return;
                }

                if (!$.fn.DataTable.isDataTable($table)) {
                    initYudisiumTable($, $table);
                }

                window.setTimeout(function () {
                    if ($.fn.DataTable.isDataTable($table)) {
                        $table.DataTable().columns.adjust().draw(false);
                    }
                }, 50);
            }

            function revealTablePanel($recap) {
                var $panel = findTablePanelForRecap($recap);

                if (!$panel.length) {
                    return Promise.resolve();
                }

                var wasHidden = $panel.hasClass('is-hidden');

                return loadDeferredTable($, $panel).then(function () {
                    if (wasHidden) {
                        $panel.removeClass('is-hidden');
                    }

                    initTableInPanel($, $panel);
                });
            }

            function applyRecapLetterFilter($recap, letter) {
                var tableId = $recap.data('yudisium-table-id');
                var $panel = findTablePanelForRecap($recap);

                revealTablePanel($recap).then(function () {
                    var $table = $('#' + tableId);

                    if (!$table.length) {
                        return;
                    }

                    setTableFilters(tableId, 'all', letter);
                    clearRecapStatFilter($panel);

                    $recap.find('.js-yudisium-recap-letter-filter').each(function () {
                        var isActive = $(this).data('letter') === letter;
                        $(this).toggleClass('is-active', isActive).attr('aria-pressed', isActive ? 'true' : 'false');
                    });

                    $recap.find('.js-yudisium-recap-letter-clear').prop('hidden', false);
                    redrawYudisiumTable($table);
                });
            }

            function applyRecapPassFilter($recap, pass) {
                var tableId = $recap.data('yudisium-table-id');
                var $panel = findTablePanelForRecap($recap);

                revealTablePanel($recap).then(function () {
                    var $table = $('#' + tableId);

                    if (!$table.length) {
                        return;
                    }

                    setTableFilters(tableId, pass, 'all');
                    clearRecapLetterFilter($panel);
                    syncRecapStatFromSelect($panel, pass);
                    redrawYudisiumTable($table);
                });
            }

            function initRecapStatFilters($) {
                $(document).off('click.yudisiumRecapStat').on('click.yudisiumRecapStat', '.js-yudisium-recap-stat-filter', function () {
                    var $btn = $(this);
                    var $recap = $btn.closest('[data-yudisium-recap]');
                    var pass = $btn.data('pass') || 'all';
                    var $table = $('#' + $recap.data('yudisium-table-id'));
                    var $panel = $table.closest('[data-yudisium-table-panel]');
                    var tableId = $recap.data('yudisium-table-id');

                    if ($btn.hasClass('is-active')) {
                        revealTablePanel($recap).then(function () {
                            setTableFilters(tableId, 'all', 'all');
                            clearRecapFilters($panel);
                            redrawYudisiumTable($('#' + tableId));
                        });
                        return;
                    }

                    applyRecapPassFilter($recap, pass);
                });
            }

            function initRecapLetterFilters($) {
                $(document).off('click.yudisiumRecapLetter').on('click.yudisiumRecapLetter', '.js-yudisium-recap-letter-filter', function () {
                    var $btn = $(this);
                    var $recap = $btn.closest('[data-yudisium-recap]');
                    var letter = $btn.data('letter');

                    if ($btn.hasClass('is-active')) {
                        var tableId = $recap.data('yudisium-table-id');
                        var $panel = findTablePanelForRecap($recap);
                        revealTablePanel($recap).then(function () {
                            setTableFilters(tableId, 'all', 'all');
                            clearRecapLetterFilter($panel);
                            redrawYudisiumTable($('#' + tableId));
                        });
                        return;
                    }

                    applyRecapLetterFilter($recap, letter);
                });

                $(document).off('click.yudisiumRecapLetterClear').on('click.yudisiumRecapLetterClear', '.js-yudisium-recap-letter-clear', function () {
                    var $recap = $(this).closest('[data-yudisium-recap]');
                    var $table = $('#' + $recap.data('yudisium-table-id'));
                    var $panel = $table.closest('[data-yudisium-table-panel]');

                    setTableFilters($recap.data('yudisium-table-id'), 'all', 'all');
                    clearRecapFilters($panel);
                    redrawYudisiumTable($table);
                });
            }

            function initJurusanTableFilters($, $table, $panel) {
                var tableId = $table.attr('id');

                if (!tableId) {
                    return;
                }

                ensureFilterState(tableId);
            }

            function scheduleYudisiumTableInit($, $table, callback) {
                var run = function () {
                    callback();
                };

                if (typeof window.requestIdleCallback === 'function') {
                    window.requestIdleCallback(run, { timeout: 800 });
                } else {
                    window.setTimeout(run, 0);
                }
            }

            function loadDeferredRecap($, $host) {
                if (!$host.length || $host.data('yudisiumRecapLoaded')) {
                    return Promise.resolve();
                }

                var url = $host.attr('data-yudisium-recap-url');
                if (!url) {
                    return Promise.resolve();
                }

                if ($host.data('yudisiumRecapLoading')) {
                    return $host.data('yudisiumRecapLoading');
                }

                var request = fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'text/html',
                    },
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal memuat rekap.');
                        }
                        return response.text();
                    })
                    .then(function (html) {
                        $host.html(html);
                        $host.data('yudisiumRecapLoaded', true);
                    })
                    .catch(function () {
                        $host.html('<div class="alert alert-warning mb-0 py-2 small">Rekap nilai gagal dimuat.</div>');
                    })
                    .finally(function () {
                        $host.removeData('yudisiumRecapLoading');
                    });

                $host.data('yudisiumRecapLoading', request);

                return request;
            }

            function initRecapHostsInScope($, $scope) {
                $scope.find('[data-yudisium-recap-url]').each(function () {
                    loadDeferredRecap($, $(this));
                });
            }

            function initYudisiumTable($, $table) {
                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy(false);
                }

                var isJurusan = $table.hasClass('js-yudisium-jurusan-table');
                var $panel = $table.closest('[data-yudisium-table-panel]');

                var dt = $table.DataTable({
                    autoWidth: false,
                    responsive: false,
                    deferRender: true,
                    processing: true,
                    orderClasses: false,
                    paging: true,
                    searching: true,
                    lengthChange: true,
                    info: true,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    dom: '<"yudisium-dt-header"lf>rt<"yudisium-dt-footer"ip>',
                    order: [[0, 'asc']],
                    columnDefs: [
                        { orderable: false, targets: [1, 2] },
                    ],
                    language: {
                        search: 'Cari:',
                        searchPlaceholder: 'Nama mahasiswa…',
                        lengthMenu: 'Tampilkan _MENU_ baris',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ baris',
                        infoEmpty: 'Menampilkan 0–0 dari 0 baris',
                        infoFiltered: '(disaring dari _MAX_ baris)',
                        zeroRecords: 'Tidak ada data yang cocok',
                        emptyTable: 'Belum ada data',
                        paginate: {
                            first: 'Awal',
                            previous: 'Sebelumnya',
                            next: 'Berikutnya',
                            last: 'Akhir',
                        },
                    },
                });

                if (isJurusan && $panel.length) {
                    initJurusanTableFilters($, $table, $panel);
                }

                return dt;
            }

            function isVisibleTabPane($table) {
                var $pane = $table.closest('.tab-pane');

                return $pane.length === 0 || $pane.hasClass('active') || $pane.hasClass('show');
            }

            function initTablesInScope($, $scope) {
                initRecapHostsInScope($, $scope);

                $scope.find('.js-yudisium-table').each(function () {
                    var $table = $(this);

                    if (!isVisibleTabPane($table)) {
                        return;
                    }

                    if ($table.closest('[data-yudisium-table-panel]').hasClass('is-hidden')) {
                        return;
                    }

                    scheduleYudisiumTableInit($, $table, function () {
                        if (!$.fn.DataTable.isDataTable($table)) {
                            initYudisiumTable($, $table);
                        }
                    });
                });
            }

            window.initYudisiumDataTables = function () {
                var $ = window.jQuery;

                if (!$ || !$.fn.DataTable) {
                    return;
                }

                installJurusanFilterSearch($);
                initRecapStatFilters($);
                initRecapLetterFilters($);

                $('.js-yudisium-table').each(function () {
                    var $table = $(this);

                    if (!isVisibleTabPane($table)) {
                        return;
                    }

                    if ($table.closest('[data-yudisium-table-panel]').hasClass('is-hidden')) {
                        return;
                    }

                    scheduleYudisiumTableInit($, $table, function () {
                        if (!$.fn.DataTable.isDataTable($table)) {
                            initYudisiumTable($, $table);
                        }
                    });
                });

                window.setTimeout(function () {
                    initRecapHostsInScope($, $('.tab-pane.active.show, .tab-pane.active'));
                }, 120);
            };

            function boot() {
                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.DataTable) {
                    window.initYudisiumDataTables();
                    bindTabHandlers(window.jQuery);
                    return;
                }

                window.addEventListener('load', function () {
                    window.initYudisiumDataTables();
                    bindTabHandlers(window.jQuery);
                });
            }

            function loadLazyYudisiumTab($, $pane) {
                if ($pane.attr('data-yudisium-tab-loaded') === '1') {
                    return Promise.resolve();
                }

                var url = $pane.attr('data-yudisium-tab-url');

                if (!url) {
                    return Promise.resolve();
                }

                if ($pane.data('yudisiumTabLoading')) {
                    return $pane.data('yudisiumTabLoading');
                }

                var request = fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'text/html',
                    },
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal memuat data tab.');
                        }

                        return response.text();
                    })
                    .then(function (html) {
                        $pane.html(html);
                        $pane.attr('data-yudisium-tab-loaded', '1');
                        $pane.data('yudisium-tab-loaded', 1);
                        delete $pane[0].dataset.yudisiumTabUrl;
                        initRecapHostsInScope($, $pane);
                    })
                    .catch(function () {
                        $pane.html(
                            '<div class="alert alert-danger m-3">Gagal memuat data tab. Silakan refresh halaman.</div>'
                        );
                    })
                    .finally(function () {
                        $pane.removeData('yudisiumTabLoading');
                    });

                $pane.data('yudisiumTabLoading', request);

                return request;
            }

            function bindTabHandlers($) {
                if (!$) {
                    return;
                }

                $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (event) {
                    var targetId = $(event.target).attr('data-bs-target');

                    if (!targetId) {
                        return;
                    }

                    var $pane = $(targetId);

                    loadLazyYudisiumTab($, $pane).then(function () {
                        initTablesInScope($, $pane);

                        $pane.find('.js-yudisium-table').each(function () {
                            var $table = $(this);
                            if ($.fn.DataTable.isDataTable($table)) {
                                $table.DataTable().columns.adjust().draw(false);
                            }
                        });
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', boot);
            } else {
                boot();
            }
        })();
    </script>
@endpush
