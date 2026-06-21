<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * national_id is optional at quick reception, so it must allow NULL.
     * (MySQL unique indexes permit multiple NULLs.)
     *
     * MODIFY is MySQL-specific; skip on other drivers.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE customers MODIFY national_id VARCHAR(20) NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE customers MODIFY national_id VARCHAR(20) NOT NULL');
    }
};
