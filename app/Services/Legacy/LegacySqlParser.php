<?php

namespace App\Services\Legacy;

use Illuminate\Support\Facades\File;

class LegacySqlParser
{
    private string $sql;

    public function __construct(string $sqlPath)
    {
        if (! File::exists($sqlPath)) {
            throw new \RuntimeException("Legacy SQL dosyası bulunamadı: {$sqlPath}");
        }

        $this->sql = File::get($sqlPath);
    }

    public static function fromDefaultPath(): self
    {
        $candidates = [
            database_path('legacy/if0_42172821_lojman.sql'),
            base_path('if0_42172821_lojman (2).sql'),
        ];

        foreach ($candidates as $path) {
            if (File::exists($path)) {
                return new self($path);
            }
        }

        throw new \RuntimeException('Legacy SQL dosyası bulunamadı.');
    }

    /** @return list<array{id:int,name:string,gender:string,department:string}> */
    public function employees(): array
    {
        $section = $this->section('employees');
        $rows = [];

        if (preg_match_all("/\((\d+),\s*'((?:[^'\\\\]|\\\\.)*)',\s*'((?:[^'\\\\]|\\\\.)*)',\s*'((?:[^'\\\\]|\\\\.)*)'\)/u", $section, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $rows[] = [
                    'id' => (int) $m[1],
                    'name' => stripcslashes($m[2]),
                    'gender' => stripcslashes($m[3]),
                    'department' => stripcslashes($m[4]),
                ];
            }
        }

        return $rows;
    }

    /** @return list<array{id:int,floor_name:string,room_no:string,capacity:int,gender:string}> */
    public function rooms(): array
    {
        $section = $this->section('rooms');
        $rows = [];

        if (preg_match_all("/\((\d+),\s*'((?:[^'\\\\]|\\\\.)*)',\s*'((?:[^'\\\\]|\\\\.)*)',\s*(\d+),\s*'((?:[^'\\\\]|\\\\.)*)'\)/u", $section, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $rows[] = [
                    'id' => (int) $m[1],
                    'floor_name' => stripcslashes($m[2]),
                    'room_no' => stripcslashes($m[3]),
                    'capacity' => (int) $m[4],
                    'gender' => stripcslashes($m[5]),
                ];
            }
        }

        return $rows;
    }

    /** @return list<array{id:int,employee_id:int,room_id:int}> */
    public function assignments(): array
    {
        $section = $this->section('room_assignments');
        $rows = [];

        if (preg_match_all('/\((\d+),\s*(\d+),\s*(\d+)\)/', $section, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $rows[] = [
                    'id' => (int) $m[1],
                    'employee_id' => (int) $m[2],
                    'room_id' => (int) $m[3],
                ];
            }
        }

        return $rows;
    }

    private function section(string $table): string
    {
        if (! preg_match('/INSERT INTO `'.$table.'`.*?;/s', $this->sql, $match)) {
            return '';
        }

        return $match[0];
    }
}
