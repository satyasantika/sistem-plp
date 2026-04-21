<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Map;
use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\DataTables\MapDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/maps-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/maps-create', ['only' => ['create','store','importCreate','importTemplate','importExcel']]);
        $this->middleware('permission:konfigurasi/maps-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/maps-delete', ['only' => ['destroy']]);
    }

    public function index(MapDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.map');
    }

    public function create()
    {
        $map = new Map();
        return view('konfigurasi.map-action', array_merge(
            ['map'=> $map],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Map::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data telah ditambahkan'
        ]);
    }

    public function edit(Map $map)
    {
        return view('konfigurasi.map-action', array_merge(
            ['map'=> $map],
            $this->_dataSelection($map->subject_id),
            ));
    }

    public function update(Request $request, Map $map)
    {
        $data = $request->all();
        $map->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah diperbarui'
        ]);
    }

    public function destroy(Map $map)
    {
        $name = $map->candidate_name;
        $map->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus'
        ]);
    }

    public function importCreate()
    {
        return view('konfigurasi.import-map-action', [
            'title' => request('title', 'Import Maps'),
        ]);
    }

    public function importTemplate()
    {
        $schools = School::select('id', 'name')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name')->orderBy('name')->get();
        $fileName = 'template-import-maps.xlsx';

        return response()->streamDownload(function () use ($schools, $subjects) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('template');

            $headers = ['nim_mahasiswa', 'nidn_dosen', 'nip_guru', 'nama_sekolah', 'mapel', 'year'];
            $sampleRow = ['2200001', '19870001', '19750001', optional($schools->first())->name ?? '', optional($subjects->first())->name ?? '', date('Y')];

            foreach ($headers as $index => $header) {
                $column = chr(65 + $index);
                $sheet->setCellValue($column.'1', $header);
                $sheet->getStyle($column.'1')->getFont()->setBold(true);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $sheet->setCellValue($column.'2', $sampleRow[$index]);
            }

            $lookupSheet = $spreadsheet->createSheet();
            $lookupSheet->setTitle('lookup');
            $lookupSheet->setCellValue('A1', 'nama_sekolah');
            $lookupSheet->setCellValue('B1', 'school_id');
            $lookupSheet->setCellValue('D1', 'mapel');
            $lookupSheet->setCellValue('E1', 'subject_id');

            foreach ($schools as $rowIndex => $school) {
                $excelRow = $rowIndex + 2;
                $lookupSheet->setCellValue('A'.$excelRow, $school->name);
                $lookupSheet->setCellValue('B'.$excelRow, $school->id);
            }

            foreach ($subjects as $rowIndex => $subject) {
                $excelRow = $rowIndex + 2;
                $lookupSheet->setCellValue('D'.$excelRow, $subject->name);
                $lookupSheet->setCellValue('E'.$excelRow, $subject->id);
            }

            $lastSchoolRow = max($schools->count() + 1, 2);
            $lastSubjectRow = max($subjects->count() + 1, 2);
            $spreadsheet->addNamedRange(new NamedRange('school_options', $lookupSheet, '$A$2:$A$'.$lastSchoolRow));
            $spreadsheet->addNamedRange(new NamedRange('subject_options', $lookupSheet, '$D$2:$D$'.$lastSubjectRow));

            for ($row = 2; $row <= 300; $row++) {
                $schoolValidation = $sheet->getCell('D'.$row)->getDataValidation();
                $schoolValidation->setType(DataValidation::TYPE_LIST);
                $schoolValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $schoolValidation->setAllowBlank(false);
                $schoolValidation->setShowDropDown(true);
                $schoolValidation->setShowInputMessage(true);
                $schoolValidation->setPromptTitle('Pilih Nama Sekolah');
                $schoolValidation->setPrompt('Nama sekolah diambil dari tabel schools.');
                $schoolValidation->setShowErrorMessage(true);
                $schoolValidation->setErrorTitle('Nama sekolah tidak valid');
                $schoolValidation->setError('Pilih nama sekolah dari dropdown yang tersedia.');
                $schoolValidation->setFormula1('=school_options');

                $subjectValidation = $sheet->getCell('E'.$row)->getDataValidation();
                $subjectValidation->setType(DataValidation::TYPE_LIST);
                $subjectValidation->setErrorStyle(DataValidation::STYLE_STOP);
                $subjectValidation->setAllowBlank(false);
                $subjectValidation->setShowDropDown(true);
                $subjectValidation->setShowInputMessage(true);
                $subjectValidation->setPromptTitle('Pilih Mapel');
                $subjectValidation->setPrompt('Mapel diambil dari tabel subjects.');
                $subjectValidation->setShowErrorMessage(true);
                $subjectValidation->setErrorTitle('Mapel tidak valid');
                $subjectValidation->setError('Pilih mapel dari dropdown yang tersedia.');
                $subjectValidation->setFormula1('=subject_options');
            }

            $lookupSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importExcel(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        $file = $request->file('file');
        $tempPath = $file->storeAs('temp-imports', Str::uuid().'.'.$file->getClientOriginalExtension());
        $absolutePath = storage_path('app/'.$tempPath);
        $insertedCount = 0;

        try {
            $rows = $this->extractImportRows($absolutePath);
            $maps = $this->prepareImportRows($rows);

            DB::transaction(function () use ($maps, &$insertedCount) {
                foreach ($maps as $row) {
                    Map::create($row);
                    $insertedCount++;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Import maps berhasil. Total data tersimpan: '.$insertedCount.'.',
            ]);
        } finally {
            Storage::delete($tempPath);
        }
    }

    private function _dataSelection($subject_id = '')
    {
        $student_in_map = Map::whereNotNull('student_id')->where('subject_id',$subject_id)->pluck('student_id');
        $students = User::role('mahasiswa')->where('subject_id',$subject_id)->whereNotIn('id',$student_in_map);
        $lectures = User::role('dosen')->where('subject_id',$subject_id);
        $teachers = User::role('guru')->where('subject_id',$subject_id);
        if ($subject_id == '') {
            $student_in_map = Map::whereNotNull('student_id')->pluck('student_id');
            $students = User::role('mahasiswa')->whereNotIn('id',$student_in_map);
            $lectures = User::role('dosen');
            $teachers = User::role('guru');
        }

        return [
            'students' => $students->select('id','name')->orderBy('name')->get(),
            'lectures' => $lectures->select('id','name')->orderBy('name')->get(),
            'teachers' => $teachers->select('id','name')->orderBy('name')->get(),
            'schools' =>  School::select('id','name')->orderBy('id')->get(),
            'subjects' =>  Subject::select('id','name')->orderBy('name')->get(),
        ];
    }

    private function extractImportRows(string $filePath): array
    {
        $requiredHeadings = ['nim_mahasiswa', 'nidn_dosen', 'nip_guru', 'nama_sekolah', 'mapel', 'year'];
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray('', true, true, false);

        if ($rows === []) {
            throw LaravelValidationException::withMessages([
                'file' => ['File Excel kosong.'],
            ]);
        }

        $headings = collect(array_shift($rows))
            ->take(count($requiredHeadings))
            ->map(function ($heading) {
                return Str::of((string) $heading)->trim()->lower()->replace(' ', '_')->toString();
            })
            ->values()
            ->all();

        if ($headings !== $requiredHeadings) {
            throw LaravelValidationException::withMessages([
                'file' => ['Kolom template harus persis: '.implode(', ', $requiredHeadings).'.'],
            ]);
        }

        $preparedRows = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $item = [
                'row_number' => $rowNumber,
                'nim_mahasiswa' => trim((string) ($row[0] ?? '')),
                'nidn_dosen' => trim((string) ($row[1] ?? '')),
                'nip_guru' => trim((string) ($row[2] ?? '')),
                'nama_sekolah' => trim((string) ($row[3] ?? '')),
                'mapel' => trim((string) ($row[4] ?? '')),
                'year' => trim((string) ($row[5] ?? '')),
            ];

            if (collect($item)->except('row_number')->every(fn ($value) => $value === '')) {
                continue;
            }

            $preparedRows[] = $item;
        }

        if ($preparedRows === []) {
            throw LaravelValidationException::withMessages([
                'file' => ['Tidak ada data yang bisa diimport.'],
            ]);
        }

        return $preparedRows;
    }

    private function prepareImportRows(array $rows): array
    {
        $studentUsernames = collect($rows)->pluck('nim_mahasiswa')->filter()->unique()->values();
        $lectureUsernames = collect($rows)->pluck('nidn_dosen')->filter()->unique()->values();
        $teacherUsernames = collect($rows)->pluck('nip_guru')->filter()->unique()->values();
        $schoolNames = collect($rows)->pluck('nama_sekolah')->filter()->unique()->values();
        $subjectNames = collect($rows)->pluck('mapel')->filter()->unique()->values();

        $students = User::role('mahasiswa')->whereIn('username', $studentUsernames)->get()->keyBy('username');
        $lectures = User::role('dosen')->whereIn('username', $lectureUsernames)->get()->keyBy('username');
        $teachers = User::role('guru')->whereIn('username', $teacherUsernames)->get()->keyBy('username');
        $schools = School::whereIn('name', $schoolNames)->get()->keyBy('name');
        $subjects = Subject::whereIn('name', $subjectNames)->get()->keyBy('name');

        $errors = [];
        $compositeKeys = [];
        $importRows = [];

        foreach ($rows as $row) {
            $validator = Validator::make(
                $row,
                [
                    'nim_mahasiswa' => ['required', 'string'],
                    'nidn_dosen' => ['required', 'string'],
                    'nip_guru' => ['required', 'string'],
                    'nama_sekolah' => ['required', 'string'],
                    'mapel' => ['required', 'string'],
                    'year' => ['required', 'integer', 'digits:4'],
                ],
                [
                    'nim_mahasiswa.required' => 'nim_mahasiswa wajib diisi.',
                    'nidn_dosen.required' => 'nidn_dosen wajib diisi.',
                    'nip_guru.required' => 'nip_guru wajib diisi.',
                    'nama_sekolah.required' => 'nama_sekolah wajib diisi.',
                    'mapel.required' => 'mapel wajib diisi.',
                    'year.required' => 'year wajib diisi.',
                    'year.integer' => 'year harus berupa angka.',
                    'year.digits' => 'year harus 4 digit.',
                ]
            );

            foreach ($validator->errors()->all() as $message) {
                $errors[] = 'Baris '.$row['row_number'].': '.$message;
            }

            $student = $students->get($row['nim_mahasiswa']);
            $lecture = $lectures->get($row['nidn_dosen']);
            $teacher = $teachers->get($row['nip_guru']);
            $school = $schools->get($row['nama_sekolah']);
            $subject = $subjects->get($row['mapel']);

            if (!$student) {
                $errors[] = 'Baris '.$row['row_number'].': nim_mahasiswa '.$row['nim_mahasiswa'].' tidak ditemukan pada user role mahasiswa.';
            }

            if (!$lecture) {
                $errors[] = 'Baris '.$row['row_number'].': nidn_dosen '.$row['nidn_dosen'].' tidak ditemukan pada user role dosen.';
            }

            if (!$teacher) {
                $errors[] = 'Baris '.$row['row_number'].': nip_guru '.$row['nip_guru'].' tidak ditemukan pada user role guru.';
            }

            if (!$school) {
                $errors[] = 'Baris '.$row['row_number'].': nama_sekolah '.$row['nama_sekolah'].' tidak ditemukan.';
            }

            if (!$subject) {
                $errors[] = 'Baris '.$row['row_number'].': mapel '.$row['mapel'].' tidak ditemukan pada tabel subject.';
            }

            if (!$student || !$lecture || !$teacher || !$school || !$subject) {
                continue;
            }

            $compositeKey = implode('|', [$student->id, $lecture->id, $teacher->id, $subject->id, $school->id, $row['year']]);

            if (isset($compositeKeys[$compositeKey])) {
                $errors[] = 'Baris '.$row['row_number'].': kombinasi student_id, lecture_id, teacher_id, subject_id, school_id, dan year duplikat di file import.';
                continue;
            }

            $compositeKeys[$compositeKey] = true;

            $exists = Map::where('student_id', $student->id)
                ->where('lecture_id', $lecture->id)
                ->where('teacher_id', $teacher->id)
                ->where('school_id', $school->id)
                ->where('subject_id', $subject->id)
                ->where('year', $row['year'])
                ->exists();

            if ($exists) {
                $errors[] = 'Baris '.$row['row_number'].': data map dengan kombinasi student_id, lecture_id, teacher_id, subject_id, school_id, dan year tersebut sudah ada.';
                continue;
            }

            $importRows[] = [
                'student_id' => $student->id,
                'lecture_id' => $lecture->id,
                'teacher_id' => $teacher->id,
                'school_id' => $school->id,
                'subject_id' => $subject->id,
                'year' => (int) $row['year'],
            ];
        }

        if ($errors !== []) {
            throw LaravelValidationException::withMessages([
                'file' => array_values(array_unique($errors)),
            ]);
        }

        return $importRows;
    }

}
