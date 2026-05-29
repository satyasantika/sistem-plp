@php
    $recap = $gradeRecap ?? null;
    $recapTableId = $tableId ?? 'yudisium-jurusan-table';
@endphp
@if (!empty($recap) && ($recap['participants'] ?? 0) > 0)
    <div
        class="yudisium-jurusan-recap mb-4"
        data-yudisium-recap
        data-yudisium-table-id="{{ $recapTableId }}"
    >
        <p class="yudisium-recap-heading mb-1">Rekap nilai tersedia</p>
        <p class="yudisium-recap-hint text-muted small mb-2">Klik salah satu kartu untuk menampilkan tabel mahasiswa (dengan filter yang sesuai)</p>

        <div class="yudisium-recap-stats">
            <button
                type="button"
                class="yudisium-recap-stat js-yudisium-recap-stat-filter is-default"
                data-pass="all"
                title="Tampilkan semua peserta"
                aria-pressed="false"
            >
                <span class="yudisium-recap-stat-label">Peserta</span>
                <span class="yudisium-recap-stat-value">{{ $recap['participants'] }}</span>
            </button>
            <button
                type="button"
                class="yudisium-recap-stat js-yudisium-recap-stat-filter is-success"
                data-pass="sudah-dinilai"
                title="Filter mahasiswa yang sudah dinilai (nilai akhir final)"
                aria-pressed="false"
            >
                <span class="yudisium-recap-stat-label">Sudah dinilai</span>
                <span class="yudisium-recap-stat-value">{{ $recap['graded'] }} <small>({{ $recap['graded_percent'] }}%)</small></span>
            </button>
            <button
                type="button"
                class="yudisium-recap-stat js-yudisium-recap-stat-filter is-warning"
                data-pass="belum-lengkap"
                title="Filter mahasiswa dengan penilaian belum lengkap"
                aria-pressed="false"
            >
                <span class="yudisium-recap-stat-label">Belum lengkap</span>
                <span class="yudisium-recap-stat-value">{{ $recap['partial'] }} <small>({{ $recap['partial_percent'] }}%)</small></span>
            </button>
            <button
                type="button"
                class="yudisium-recap-stat js-yudisium-recap-stat-filter is-muted"
                data-pass="belum-dinilai"
                title="Filter mahasiswa yang belum dinilai"
                aria-pressed="false"
            >
                <span class="yudisium-recap-stat-label">Belum dinilai</span>
                <span class="yudisium-recap-stat-value">{{ $recap['ungraded'] }} <small>({{ $recap['ungraded_percent'] }}%)</small></span>
            </button>
            @if (($recap['graded'] ?? 0) > 0)
                <button
                    type="button"
                    class="yudisium-recap-stat js-yudisium-recap-stat-filter is-pass"
                    data-pass="lulus"
                    title="Filter mahasiswa lulus (nilai ≥ 61)"
                    aria-pressed="false"
                >
                    <span class="yudisium-recap-stat-label">Lulus (≥ 61)</span>
                    <span class="yudisium-recap-stat-value">{{ $recap['pass'] }} <small>({{ $recap['pass_percent'] }}% dari yang dinilai)</small></span>
                </button>
                <button
                    type="button"
                    class="yudisium-recap-stat js-yudisium-recap-stat-filter is-fail"
                    data-pass="tidak-lulus"
                    title="Filter mahasiswa tidak lulus"
                    aria-pressed="false"
                >
                    <span class="yudisium-recap-stat-label">Tidak lulus</span>
                    <span class="yudisium-recap-stat-value">{{ $recap['fail'] }} <small>({{ $recap['fail_percent'] }}% dari yang dinilai)</small></span>
                </button>
            @endif
        </div>

        @if (($recap['graded'] ?? 0) > 0)
            <div class="yudisium-recap-letters">
                <div class="yudisium-recap-letters-toolbar">
                    <p class="yudisium-recap-subheading mb-0">Distribusi huruf — klik untuk filter tabel</p>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary js-yudisium-recap-letter-clear"
                        hidden
                    >
                        Semua nilai
                    </button>
                </div>
                <div class="yudisium-recap-letter-grid">
                    @foreach ($recap['letters'] as $item)
                        @if ($item['count'] > 0)
                            <button
                                type="button"
                                class="yudisium-recap-letter-item js-yudisium-recap-letter-filter {{ !empty($item['is_high']) ? 'is-high' : 'is-low' }}"
                                data-letter="{{ $item['letter'] }}"
                                title="Filter mahasiswa dengan nilai huruf {{ $item['letter'] }}"
                                aria-pressed="false"
                            >
                                <span class="yudisium-recap-letter">{{ $item['letter'] }}</span>
                                <span class="yudisium-recap-letter-count">{{ $item['count'] }} mhs</span>
                                <span class="yudisium-recap-letter-pct">{{ $item['percent'] }}%</span>
                                <div class="yudisium-recap-bar" role="presentation">
                                    <div class="yudisium-recap-bar-fill" style="width: {{ min(100, $item['percent']) }}%"></div>
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <details class="yudisium-recap-scale mt-3">
            <summary class="yudisium-recap-scale-toggle">Keterangan rentang nilai (persentase → huruf)</summary>
            <div class="table-responsive mt-2">
                <table class="table table-sm table-bordered yudisium-recap-scale-table mb-0">
                    <thead>
                        <tr>
                            <th>Huruf</th>
                            <th>Rentang nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recap['scale'] as $band)
                            <tr class="{{ in_array($band['letter'], ['A', 'A-', 'B+', 'B'], true) ? 'table-light' : '' }}">
                                <td class="fw-bold">{{ $band['letter'] }}</td>
                                <td>{{ $band['label'] }}</td>
                                <td class="small text-muted">
                                    @if ($band['letter'] === 'B')
                                        Batas minimal lulus
                                    @elseif (in_array($band['letter'], ['A', 'A-', 'B+'], true))
                                        Kategori sangat baik / baik
                                    @else
                                        Di bawah standar minimal lulus
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    </div>
@endif
