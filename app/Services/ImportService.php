<?php

namespace App\Services;

use App\Enums\EmployeeStatus;
use App\Enums\Gender;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportService
{
    public function importEmployees(UploadedFile $file, ?User $actor = null): array
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $header = array_shift($rows);
        $map = $this->mapColumns($header);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::transaction(function () use ($rows, $map, $actor, &$imported, &$skipped, &$errors) {
            foreach ($rows as $lineNumber => $row) {
                $line = $lineNumber + 2;

                try {
                    $fullName = trim((string) ($row[$map['full_name']] ?? ''));
                    if ($fullName === '') {
                        $skipped++;
                        continue;
                    }

                    $personnelNumber = trim((string) ($row[$map['personnel_number']] ?? ''));
                    if ($personnelNumber === '') {
                        $personnelNumber = app(EmployeeService::class)->nextPersonnelNumber();
                    }

                    if (Employee::query()->where('personnel_number', $personnelNumber)->exists()) {
                        $skipped++;
                        $errors[] = "Satır {$line}: {$personnelNumber} zaten kayıtlı.";
                        continue;
                    }

                    $gender = Gender::fromLegacy(trim((string) ($row[$map['gender']] ?? '')));
                    if (! $gender) {
                        throw new \InvalidArgumentException('Geçersiz cinsiyet.');
                    }
                    $deptName = trim((string) ($row[$map['department']] ?? '')) ?: 'GENEL';
                    $department = Department::query()->firstOrCreate(
                        ['code' => Str::upper(Str::slug($deptName, '_'))],
                        ['name' => $deptName, 'is_active' => true]
                    );

                    Employee::query()->create([
                        'personnel_number' => $personnelNumber,
                        'full_name' => $fullName,
                        'gender' => $gender,
                        'department_id' => $department->id,
                        'phone' => trim((string) ($row[$map['phone']] ?? '')) ?: null,
                        'email' => trim((string) ($row[$map['email']] ?? '')) ?: null,
                        'job_title' => trim((string) ($row[$map['job_title']] ?? '')) ?: null,
                        'status' => EmployeeStatus::Active,
                        'created_by' => $actor?->id,
                    ]);

                    $imported++;
                } catch (\Throwable $e) {
                    $skipped++;
                    $errors[] = "Satır {$line}: {$e->getMessage()}";
                }
            }
        });

        return compact('imported', 'skipped', 'errors');
    }

    private function mapColumns(?array $header): array
    {
        $aliases = [
            'personnel_number' => ['sicil', 'sicil_no', 'personnel_number', 'personel_no', 'employee_code'],
            'full_name' => ['ad_soyad', 'ad soyad', 'full_name', 'name', 'isim'],
            'gender' => ['cinsiyet', 'gender'],
            'department' => ['departman', 'department', 'birim'],
            'phone' => ['telefon', 'phone', 'tel'],
            'email' => ['email', 'e-posta', 'eposta'],
            'job_title' => ['gorev', 'görev', 'job_title', 'unvan'],
        ];

        $normalized = [];
        foreach ($header as $col => $label) {
            $normalized[mb_strtolower(trim((string) $label))] = $col;
        }

        $map = [];
        foreach ($aliases as $field => $keys) {
            foreach ($keys as $key) {
                if (isset($normalized[$key])) {
                    $map[$field] = $normalized[$key];
                    break;
                }
            }
        }

        if (! isset($map['full_name'])) {
            throw new \InvalidArgumentException('Excel dosyasında ad soyad sütunu bulunamadı.');
        }

        return $map;
    }
}
