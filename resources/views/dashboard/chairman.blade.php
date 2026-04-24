<div class="content-wrapper">
	<div class="row g-3 mb-3">
		<div class="col-6 col-lg-3">
			<div class="card h-100">
				<div class="card-header">
					<h5>Total Ploting</h5>
				</div>
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="fs-4 fw-bold">{{ $adminOverview['total_maps'] ?? 0 }}</div>
						<span class="badge bg-primary">Tahun {{ $activeYear }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card h-100">
				<div class="card-header">
					<h5>Mahasiswa Terplot</h5>
				</div>
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="fs-4 fw-bold">{{ $adminOverview['filled_students'] ?? 0 }}</div>
						<span class="badge bg-light text-dark">{{ $adminOverview['student_fill_rate'] ?? 0 }}%</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card h-100">
				<div class="card-header">
					<h5>DPL Terplot</h5>
				</div>
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="fs-4 fw-bold">{{ $adminOverview['mapped_lectures'] ?? 0 }}</div>
						<span class="badge bg-light text-dark">Guru: {{ $adminOverview['mapped_teachers'] ?? 0 }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card h-100">
				<div class="card-header">
					<h5>Entitas Aktif</h5>
				</div>
				<div class="card-body">
					<div class="d-flex align-items-end justify-content-between">
						<div class="fs-4 fw-bold">{{ $adminOverview['active_schools'] ?? 0 }}</div>
						<span class="badge bg-light text-dark">Prodi: {{ $adminOverview['active_subjects'] ?? 0 }}</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-lg-7">
			<div class="card h-100">
				<div class="card-header">
					<h5>Ringkasan Ploting per Bidang Studi</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th>Bidang Studi</th>
									<th class="text-end">Kuota</th>
									<th class="text-end">Terisi</th>
									<th class="text-end">Sekolah</th>
									<th class="text-end">DPL</th>
									<th class="text-end">GP</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($adminSubjectSummaries as $summary)
								<tr>
									<td>{{ $summary->subjects->name ?? '-' }}</td>
									<td class="text-end">{{ $summary->quota_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->filled_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->school_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->lecture_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->teacher_count ?? 0 }}</td>
								</tr>
								@empty
								<tr>
									<td colspan="6" class="text-center text-muted">Belum ada data ringkasan bidang studi.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-5">
			<div class="card h-100">
				<div class="card-header">
					<h5>Sekolah dengan Ploting Terbanyak</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-sm">
							<thead>
								<tr>
									<th>Sekolah</th>
									<th class="text-end">Terisi</th>
									<th class="text-end">Kuota</th>
									<th class="text-end">Prodi</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($adminSchoolSummaries as $summary)
								<tr>
									<td>{{ $summary->schools->name ?? '-' }}</td>
									<td class="text-end">{{ $summary->filled_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->quota_count ?? 0 }}</td>
									<td class="text-end">{{ $summary->subject_count ?? 0 }}</td>
								</tr>
								@empty
								<tr>
									<td colspan="4" class="text-center text-muted">Belum ada data sekolah.</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
