<div class="col-auto">
    <div class="card">
        <div class="card-header">
            <h5>{{ $departmentData['title'] ?? 'Progress Penilaian DPL' }}</h5>
        </div>
        <div class="card-body">
            @php($cards = $departmentData['cards'] ?? [])
            @forelse ($cards as $index => $card)
            @if ($index === 0)
            <div data-progress-subject-switcher>
                <div class="progress-toolbar">
                    @if (count($cards) > 1)
                    <div>
                        <label for="progress-subject-select-{{ $plp_order ?? 'only' }}">Pilih Prodi</label>
                        <select id="progress-subject-select-{{ $plp_order ?? 'only' }}" class="js-progress-subject-select form-select">
                            @foreach ($cards as $subjectCard)
                                <option value="{{ $subjectCard['id'] }}">{{ $subjectCard['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                @foreach ($cards as $subjectCard)
                <div class="progress-subject-view" data-subject-id="{{ $subjectCard['id'] }}" @if($index !== 0 && $subjectCard['id'] !== $cards[0]['id']) hidden @endif>
                    <div class="progress-toolbar">
                        <div class="progress-summary-badge">
                            <span>{{ $subjectCard['name'] }}</span>
                            <span class="value">{{ $subjectCard['percent'] }}%</span>
                        </div>
                    </div>

                    <div class="table-responsive progress-panel">
                        <table class="table small-font table-striped table-hover table-sm progress-data-table">
                            <thead>
                                <tr>
                                    <th>DPL</th>
                                    <th class="text-end">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subjectCard['rows'] as $row)
                                <tr>
                                    <td>
                                        <div class="progress-person">
                                            @if (!empty($row['phone']))
                                                <a href="{{ 'http://wa.me/62' . $row['phone'] }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span class="progress-person-name">{{ $row['name'] }}</span>
                                        </div>
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
                                    <td colspan="2" class="text-center text-muted">Belum ada data progress.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="progress-toolbar mt-3 mb-2">
                        <div class="progress-summary-badge">
                            <span>Penilaian Sudah Selesai</span>
                            <span class="value">{{ count($subjectCard['completed_rows'] ?? []) }}</span>
                        </div>
                    </div>

                    <div class="table-responsive progress-panel">
                        <table class="table small-font table-striped table-hover table-sm progress-data-table">
                            <thead>
                                <tr>
                                    <th>DPL</th>
                                    <th class="text-end">Penilaian Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($subjectCard['completed_rows'] ?? []) as $row)
                                <tr>
                                    <td>
                                        <div class="progress-person">
                                            @if (!empty($row['phone']))
                                                <a href="{{ 'http://wa.me/62' . $row['phone'] }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span class="progress-person-name">{{ $row['name'] }}</span>
                                        </div>
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
                                    <td colspan="2" class="text-center text-muted">Belum ada penilai yang selesai seluruhnya.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
            @break
            @endif
            @empty
                <p class="text-muted mb-0">Belum ada data progress.</p>
            @endforelse
        </div>
    </div>
</div>
