@php
    $state = $row['display_state'] ?? null;

    if ($state === null) {
        $legacyGrade = (float) ($row['grade'] ?? 0);
        $legacyLetter = trim((string) ($row['letter'] ?? ''));
        $filled = (int) ($row['assessment_filled_slots'] ?? 0);
        $required = (int) ($row['assessment_required_slots'] ?? 0);
        $hasFinal = $legacyGrade > 0 || $legacyLetter !== '';

        if ($required > 0) {
            if ($filled === 0) {
                $state = 'empty';
            } elseif ($filled < $required || ! $hasFinal) {
                $state = 'partial';
            } else {
                $state = 'complete';
            }
        } elseif ($hasFinal) {
            $state = 'complete';
        } elseif ($filled > 0 || ! empty($row['has_assessment'])) {
            $state = 'partial';
        } else {
            $state = 'empty';
        }

        if ($state === 'complete') {
            $row['grade_display'] = $row['grade_display'] ?? ($legacyGrade > 0 ? number_format($legacyGrade, 2, ',', '') : null);
            $row['letter_display'] = $row['letter_display'] ?? ($legacyLetter !== '' ? $legacyLetter : null);
            $row['pass_label'] = $row['pass_label'] ?? ($legacyGrade >= 61 ? 'Lulus' : ($legacyGrade > 0 ? 'Tidak lulus' : null));
            $row['status'] = $row['status'] ?? ($legacyGrade >= 85 ? 'primary' : ($legacyGrade >= 61 ? 'success' : 'danger'));
        }
    }
@endphp
<td class="text-center yudisium-grade-cell">
    @if ($state === 'complete')
        <div class="yudisium-final-grade is-{{ $row['status'] ?? 'secondary' }}" title="Nilai akhir PLP">
            @if (!empty($row['grade_display']))
                <span class="yudisium-grade-number">{{ $row['grade_display'] }}</span>
            @endif
            @if (!empty($row['letter_display']))
                <span class="yudisium-grade-letter">{{ $row['letter_display'] }}</span>
            @endif
            @if (!empty($row['pass_label']))
                <span class="yudisium-grade-pass">{{ $row['pass_label'] }}</span>
            @endif
        </div>
    @elseif ($state === 'partial')
        <div class="yudisium-final-grade is-partial" title="Penilaian form belum lengkap; nilai akhir belum final">
            <span class="yudisium-grade-hint">Belum lengkap</span>
            @if (!empty($row['grade_display']))
                <span class="yudisium-grade-number yudisium-grade-number--draft">{{ $row['grade_display'] }}</span>
            @endif
        </div>
    @else
        <span class="yudisium-grade-empty" title="Belum ada penilaian atau nilai akhir">—</span>
    @endif
</td>
