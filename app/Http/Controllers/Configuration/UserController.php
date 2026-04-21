<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users-read', ['only' => ['index','show']]);
        $this->middleware('permission:users-create', ['only' => ['create','store','importCreate','importTemplate','importExcel']]);
        $this->middleware('permission:users-update', ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.user');
    }

    public function create()
    {
        $user = new User();

        return view('konfigurasi.user-action', array_merge(
            [ 'user' => $user ],
            $this->_dataSelection(),
        ));
    }

    public function store(UserRequest $request)
    {
        $data = $request->safe()->merge([
            'password'=> bcrypt($request->password),
        ]);
        User::create($data->all())->assignRole($request->role);
        return response()->json([
            'success' => true,
            'message' => 'User <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);

    }

    public function edit(User $user)
    {
        return view('konfigurasi.user-action', array_merge(
            [ 'user' => $user ],
            $this->_dataSelection(),
        ));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $user->fill($data)->save();
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    public function activation(User $user)
    {
        $name = strtoupper($user->name);
        // $user->is_active = $user->is_active ? 0 : 1;
        // $user->save();
        $user->can('active-read') ? $user->revokePermissionTo('active-read') : $user->givePermissionTo('active-read');
        $status = $user->can('active-read') ? 'aktiv':'non-aktiv';
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$name.'</strong> telah di'.$status.'kan'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'roles' =>  Role::all()->pluck('name')->sort(),
            'subjects' =>  Subject::select('id', 'name')->orderBy('name')->get(),
            'providers' => ['Telkomsel','Indosat Oreedoo'],
            'is_pns' => ['nonPNS','PNS','PPPK'],
            'golongans' => ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII'],
            'banks' => ['Mandiri','BRI','BJB','BTN','BCA','BNI'],
        ];
    }

    public function importCreate()
    {
        $role = $this->validateImportRole(request('role'));

        return view('konfigurasi.import-action', [
            'role' => $role,
            'title' => request('title', 'Import '.ucfirst($role)),
        ]);
    }

    public function importTemplate(Request $request)
    {
        $role = $this->validateImportRole($request->role);
        $fileName = 'template-import-user-'.$role.'.xlsx';
        $sampleRows = [
            'dosen' => ['dosen001', 'Dosen Contoh', 'dosen@example.com', 'secret123', '1'],
            'guru' => ['guru001', 'Guru Contoh', 'guru@example.com', 'secret123', '1'],
            'mahasiswa' => ['mhs001', 'Mahasiswa Contoh', 'mhs@example.com', 'secret123', '1'],
        ];
        $subjects = Subject::select('id', 'name')->orderBy('id')->get();

        return response()->streamDownload(function () use ($role, $sampleRows, $subjects) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('template');

            $headers = ['username', 'name', 'email', 'password', 'subject_id'];
            foreach ($headers as $index => $header) {
                $column = chr(65 + $index);
                $sheet->setCellValue($column.'1', $header);
                $sheet->getStyle($column.'1')->getFont()->setBold(true);
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            foreach ($sampleRows[$role] as $index => $value) {
                $column = chr(65 + $index);
                $sheet->setCellValue($column.'2', $value);
            }

            $subjectSheet = $spreadsheet->createSheet();
            $subjectSheet->setTitle('subjects');
            $subjectSheet->setCellValue('A1', 'subject_id');
            $subjectSheet->setCellValue('B1', 'subject_name');

            foreach ($subjects as $rowIndex => $subject) {
                $excelRow = $rowIndex + 2;
                $subjectSheet->setCellValue('A'.$excelRow, $subject->id);
                $subjectSheet->setCellValue('B'.$excelRow, $subject->name);
            }

            $lastSubjectRow = max($subjects->count() + 1, 2);
            $spreadsheet->addNamedRange(new NamedRange('subject_options', $subjectSheet, '$A$2:$A$'.$lastSubjectRow));

            for ($row = 2; $row <= 300; $row++) {
                $validation = $sheet->getCell('E'.$row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('Pilih Subject ID');
                $validation->setPrompt('Subject ID diambil otomatis dari daftar subject.');
                $validation->setShowErrorMessage(true);
                $validation->setErrorTitle('Subject ID tidak valid');
                $validation->setError('Pilih subject_id dari dropdown yang tersedia.');
                $validation->setFormula1('=subject_options');
            }

            $subjectSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

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
            'role' => 'required|in:dosen,guru,mahasiswa',
        ]);

        $file = $request->file('file');
        $tempPath = $file->storeAs('temp-imports', Str::uuid().'.'.$file->getClientOriginalExtension());
        $absolutePath = storage_path('app/'.$tempPath);
        $insertedCount = 0;

        try {
            $rows = $this->extractImportRows($absolutePath);
            $importRows = $this->prepareImportRows($rows);

            DB::transaction(function () use ($importRows, $validated, &$insertedCount) {
                foreach ($importRows as $row) {
                    $user = User::create([
                        'username' => $row['username'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password' => $row['password_hash'],
                        'subject_id' => $row['subject_id'],
                    ]);

                    $user->syncRoles([$validated['role']]);
                    $insertedCount++;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Import user role '.strtoupper($validated['role']).' berhasil. Total data tersimpan: '.$insertedCount.'.',
            ]);
        } finally {
            Storage::delete($tempPath);
        }
    }

    private function validateImportRole(?string $role): string
    {
        $allowedRoles = ['dosen', 'guru', 'mahasiswa'];

        abort_unless(in_array($role, $allowedRoles, true), 404);

        return $role;
    }

    private function extractImportRows(string $filePath): array
    {
        $requiredHeadings = ['username', 'name', 'email', 'password', 'subject_id'];
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
                'username' => trim((string) ($row[0] ?? '')),
                'name' => trim((string) ($row[1] ?? '')),
                'email' => trim((string) ($row[2] ?? '')),
                'password' => trim((string) ($row[3] ?? '')),
                'subject_id' => trim((string) ($row[4] ?? '')),
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
        $usernames = collect($rows)->pluck('username');
        $emails = collect($rows)->pluck('email');
        $duplicateUsernames = $usernames->filter()->countBy()->filter(fn ($count) => $count > 1)->keys()->all();
        $duplicateEmails = $emails->filter()->countBy()->filter(fn ($count) => $count > 1)->keys()->all();
        $existingUsernames = User::whereIn('username', $usernames)->pluck('username')->all();
        $existingEmails = User::whereIn('email', $emails)->pluck('email')->all();
        $errors = [];
        $importRows = [];

        foreach ($rows as $row) {
            $validator = Validator::make(
                $row,
                [
                    'username' => ['required', 'string'],
                    'name' => ['required', 'string'],
                    'email' => ['required', 'email'],
                    'password' => ['required', 'string', 'min:6'],
                    'subject_id' => ['required', 'exists:subjects,id'],
                ],
                [
                    'username.required' => 'username wajib diisi.',
                    'name.required' => 'name wajib diisi.',
                    'email.required' => 'email wajib diisi.',
                    'email.email' => 'format email tidak valid.',
                    'password.required' => 'password wajib diisi.',
                    'password.min' => 'password minimal 6 karakter.',
                    'subject_id.required' => 'subject_id wajib diisi.',
                    'subject_id.exists' => 'subject_id tidak ditemukan pada tabel subject.',
                ]
            );

            foreach ($validator->errors()->all() as $message) {
                $errors[] = 'Baris '.$row['row_number'].': '.$message;
            }

            if (in_array($row['username'], $duplicateUsernames, true)) {
                $errors[] = 'Baris '.$row['row_number'].': username '.$row['username'].' duplikat di file import.';
            }

            if (in_array($row['email'], $duplicateEmails, true)) {
                $errors[] = 'Baris '.$row['row_number'].': email '.$row['email'].' duplikat di file import.';
            }

            if (in_array($row['username'], $existingUsernames, true)) {
                $errors[] = 'Baris '.$row['row_number'].': username '.$row['username'].' sudah terdaftar.';
            }

            if (in_array($row['email'], $existingEmails, true)) {
                $errors[] = 'Baris '.$row['row_number'].': email '.$row['email'].' sudah terdaftar.';
            }

            $importRows[] = [
                'username' => $row['username'],
                'name' => $row['name'],
                'email' => $row['email'],
                'password_hash' => bcrypt($row['password']),
                'subject_id' => $row['subject_id'],
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
