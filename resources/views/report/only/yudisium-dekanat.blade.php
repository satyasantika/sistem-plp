<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian PLP</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive yudisium-table-wrap">
                        <table id="{{ $tableId ?? 'yudisium-only-dekanat-table' }}" class="table small-font table-striped table-hover table-sm yudisium-table js-yudisium-table">
                            @php
                                $letters = $dekanatSummary['letters'];
                            @endphp
                            <thead>
                                <tr>
                                    <th>Jurusan</th>
                                    <th class="text-end">Peserta</th>
                                    @foreach ($letters as $letter)
                                    <th class="text-end">{{ $letter }}</th>
                                    @endforeach
                                    <th class="text-end">Belum Dinilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dekanatSummary['rows'] as $summaryRow)
                                <tr>
                                    <th>{{ $summaryRow['subject'] }}</th>
                                    <td class="text-end">{{ $summaryRow['participants'] }}</td>
                                    @foreach ($letters as $letter)
                                        @if (in_array($letter,['A','A-','B+','B']))
                                            <td class="text-end text-primary">
                                        @else
                                            <td class="text-end text-danger">
                                        @endif
                                            {{ $summaryRow['letters'][$letter] }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $summaryRow['ungraded'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="text-primary bg-light">
                                    <th>Total:</th>
                                    <th class="text-end">{{ $dekanatSummary['totals']['participants'] }}</th>
                                    @foreach ($letters as $letter)
                                    @if (in_array($letter,['A','A-','B+','B']))
                                    <th class="text-end text-primary">
                                    @else
                                    <th class="text-end text-danger">
                                    @endif
                                        {{ $dekanatSummary['totals']['letters'][$letter] }}
                                    </th>
                                    @endforeach
                                    <th class="text-end">{{ $dekanatSummary['totals']['ungraded'] }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
