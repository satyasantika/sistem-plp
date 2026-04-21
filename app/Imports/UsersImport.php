<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function __construct(private string $role)
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (blank($row['username'] ?? null)) {
                continue;
            }

            $user = User::updateOrCreate(
                ['username' => $row['username']],
                [
                    'name' => $row['name'] ?? null,
                    'email' => $row['email'] ?? null,
                    'password' => bcrypt($row['password'] ?? 'password'),
                    'subject_id' => $row['subject_id'] ?? null,
                ]
            );

            $user->syncRoles([$this->role]);
        }
    }

    public function rules(): array
    {
        return [
            '*.username' => ['required', 'string'],
            '*.name' => ['required', 'string'],
            '*.email' => ['required', 'email'],
            '*.password' => ['required', 'string', 'min:6'],
            '*.subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.username.required' => 'Kolom username wajib diisi.',
            '*.name.required' => 'Kolom name wajib diisi.',
            '*.email.required' => 'Kolom email wajib diisi.',
            '*.email.email' => 'Format email tidak valid.',
            '*.password.required' => 'Kolom password wajib diisi.',
            '*.password.min' => 'Password minimal 6 karakter.',
            '*.subject_id.required' => 'Kolom subject_id wajib diisi.',
            '*.subject_id.integer' => 'subject_id harus berupa angka.',
            '*.subject_id.exists' => 'subject_id tidak ditemukan pada data subject.',
        ];
    }
}
