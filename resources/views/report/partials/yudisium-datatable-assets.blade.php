@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <style>
        .yudisium-table-wrap {
            border-radius: 14px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            overflow: hidden;
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
        .yudisium-table-wrap .dataTables_length select {
            border: 1px solid #c8d7eb;
            border-radius: 10px;
            background: #ffffff;
            color: #29405f;
            padding: 6px 10px;
        }

        .yudisium-table-wrap .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid transparent !important;
        }

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

        .yudisium-chip.is-actor {
            font-weight: 700;
            letter-spacing: .2px;
        }

        body.dark .yudisium-table-wrap {
            border-color: rgba(173, 193, 223, 0.2);
            background: linear-gradient(180deg, #1a2639 0%, #162233 100%);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.32);
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
        body.dark .yudisium-table-wrap .dataTables_length select {
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
    </style>
@endpush

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js" defer></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js" defer></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js" defer></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if (typeof $ === 'undefined' || !$.fn.DataTable) {
                return;
            }

            $('.js-yudisium-table').each(function () {
                var $table = $(this);

                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                $table.DataTable({
                    autoWidth: false,
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    order: [],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        zeroRecords: 'Data tidak ditemukan',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Berikutnya'
                        }
                    }
                });
            });

            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function (tabTrigger) {
                tabTrigger.addEventListener('shown.bs.tab', function () {
                    if (!$.fn.DataTable) {
                        return;
                    }

                    $('.js-yudisium-table').each(function () {
                        if ($.fn.DataTable.isDataTable(this)) {
                            $(this).DataTable().columns.adjust().responsive.recalc();
                        }
                    });
                });
            });
        });
    </script>
@endpush
