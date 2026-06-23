<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('placements')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        $duplicateEmployeeIds = DB::table('placements')
            ->select('employee_id')
            ->where('is_active', true)
            ->groupBy('employee_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('employee_id');

        foreach ($duplicateEmployeeIds as $employeeId) {
            $keepId = DB::table('placements')
                ->where('employee_id', $employeeId)
                ->where('is_active', true)
                ->orderByDesc('id')
                ->value('id');

            DB::table('placements')
                ->where('employee_id', $employeeId)
                ->where('is_active', true)
                ->where('id', '!=', $keepId)
                ->update(['is_active' => false]);
        }

        $indexName = 'placements_one_active_employee';

        if ($driver === 'pgsql') {
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$indexName} ON placements (employee_id) WHERE is_active = true");
        } elseif ($driver === 'sqlite') {
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS {$indexName} ON placements (employee_id) WHERE is_active = 1");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        $indexName = 'placements_one_active_employee';

        if (in_array($driver, ['pgsql', 'sqlite'], true)) {
            DB::statement("DROP INDEX IF EXISTS {$indexName}");
        }
    }
};
