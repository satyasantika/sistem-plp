@includeWhen(auth()->user()->hasAnyRole('ketua','dekanat') && !empty($tab['dekanatSummary']), 'report.yudisium-dekanat', [
    'plp_order' => $tab['plp_order'],
    'plp_label' => $tab['plp_label'],
    'dekanatSummary' => $tab['dekanatSummary'],
    'tableId' => 'yudisium-dekanat-table-' . $tab['key'],
])

@includeWhen(auth()->user()->hasRole('kajur'), 'report.yudisium-jurusan', [
    'plp_order' => $tab['plp_order'],
    'plp_label' => $tab['plp_label'],
    'jurusanRows' => $tab['jurusanRows'],
    'lectureForms' => $tab['lectureForms'],
    'teacherForms' => $tab['teacherForms'],
    'gradeRecap' => $tab['gradeRecap'] ?? null,
    'tableId' => 'yudisium-jurusan-table-' . $tab['key'],
])
