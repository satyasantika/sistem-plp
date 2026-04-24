<div class="col-auto">
    <div class="card">
        <div class="card-header">
            <h5>{{ $schoolData['title'] ?? 'Progress Penilaian GP' }}</h5>
        </div>
        <div class="card-body">
            @php
                $cards = $schoolData['cards'] ?? [];
                $completedSchoolRows = 0;
                foreach ($cards as $card) {
                    $completedSchoolRows += count($card['completed_rows'] ?? []);
                }
            @endphp
            <div class="table-responsive progress-panel">
                <table class="table small-font table-striped table-hover table-sm progress-data-table">
                    <thead>
                        <tr>
                            <th>Sekolah</th>
                            <th>Guru Pamong</th>
                            <th class="text-center">Progres Sekolah</th>
                            <th class="text-end">Progress Form</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cards as $card)
                            @forelse ($card['rows'] as $row)
                            <tr>
                                <td>
                                    <span class="progress-person-name">{{ $card['name'] }}</span>
                                </td>
                                <td>
                                    <div class="progress-person">
                                        @if (!empty($row['phone']))
                                            <a href="{{ 'http://wa.me/62' . $row['phone'] }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        <span class="progress-person-name">{{ $row['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="progress-summary-badge">
                                        <span class="value">{{ $card['percent'] }}%</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-statuses">
                                        @foreach ($row['statuses'] as $status)
                                            <span class="progress-status-chip is-{{ $status['status'] }}"><i class="{{ $status['icon'] }}"></i> {{ $status['label'] }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>{{ $card['name'] }}</td>
                                <td colspan="3" class="text-center text-muted">Belum ada data progress.</td>
                            </tr>
                            @endforelse
            @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data progress.</td>
                        </tr>
            @endforelse
                    </tbody>
                </table>
            </div>

            <div class="progress-toolbar mt-3 mb-2">
                <div class="progress-summary-badge">
                    <span>Penilaian Sudah Selesai</span>
                    <span class="value">{{ $completedSchoolRows }}</span>
                </div>
            </div>

            <div class="table-responsive progress-panel">
                <table class="table small-font table-striped table-hover table-sm progress-data-table">
                    <thead>
                        <tr>
                            <th>Sekolah</th>
                            <th>Guru Pamong</th>
                            <th class="text-center">Progres Sekolah</th>
                            <th class="text-end">Penilaian Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cards as $card)
                            @forelse (($card['completed_rows'] ?? []) as $row)
                            <tr>
                                <td>
                                    <span class="progress-person-name">{{ $card['name'] }}</span>
                                </td>
                                <td>
                                    <div class="progress-person">
                                        @if (!empty($row['phone']))
                                            <a href="{{ 'http://wa.me/62' . $row['phone'] }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        <span class="progress-person-name">{{ $row['name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="progress-summary-badge">
                                        <span class="value">{{ $card['percent'] }}%</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="progress-statuses">
                                        @foreach ($row['statuses'] as $status)
                                            <span class="progress-status-chip is-{{ $status['status'] }}"><i class="{{ $status['icon'] }}"></i> {{ $status['label'] }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        @empty
                        @endforelse

                        @if ($completedSchoolRows === 0)
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada penilai yang selesai seluruhnya.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
