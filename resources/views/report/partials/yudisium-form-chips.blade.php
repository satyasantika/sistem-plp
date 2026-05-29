@php
    $formStatuses = $formStatuses ?? [];
    $formGrades = $formGrades ?? [];
    $chipRole = $chipRole ?? 'lecture';
    $compact = $compact ?? true;
    $statusTitles = [
        'empty' => 'Belum dinilai',
        'partial' => 'Belum lengkap (sebagian slot)',
        'filled' => 'Sudah dinilai',
    ];
    $pendingForms = [];
    $filledCount = 0;

    foreach ($forms as $form) {
        $status = $formStatuses[$form] ?? 'empty';
        if ($status === 'filled') {
            $filledCount++;
            if (! $compact) {
                $pendingForms[] = ['form' => $form, 'status' => $status];
            }
            continue;
        }
        $pendingForms[] = ['form' => $form, 'status' => $status];
    }
@endphp
@foreach ($pendingForms as $item)
    @php
        $form = $item['form'];
        $status = $item['status'];
        $statusClass = match ($status) {
            'empty' => 'is-form-empty',
            'partial' => 'is-form-partial',
            default => 'is-form-filled',
        };
        $grade = (float) ($formGrades[$form] ?? 0);
    @endphp
    <span
        class="yudisium-chip is-{{ $chipRole }} {{ $statusClass }}"
        title="{{ $statusTitles[$status] ?? '' }} — {{ $form }}"
    >
        {{ substr($form, -2) }}
        @if ($status === 'empty')
            <span class="chip-value chip-value--empty">—</span>
        @else
            <span class="chip-value">{{ $grade > 0 ? $grade : '0' }}</span>
        @endif
    </span>
@endforeach
@if ($filledCount > 0)
    <span class="yudisium-chip is-{{ $chipRole }} is-form-complete-summary" title="{{ $filledCount }} form sudah dinilai">
        ✓ {{ $filledCount }} selesai
    </span>
@endif
@if ($pendingForms === [] && $filledCount > 0)
    <span class="yudisium-chip is-{{ $chipRole }} is-form-all-done">Semua form dinilai</span>
@endif
