<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the QA stage to the card lifecycle:
     * pending -> in_progress -> ready_for_qa -> ready -> delivered (+ waiting_parts)
     *
     * ENUM MODIFY is MySQL-specific; on other drivers (e.g. sqlite) the
     * status column is just a string, so nothing needs to change.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE maintenance_cards MODIFY status ENUM(
            'pending','in_progress','waiting_parts','ready_for_qa','ready','delivered'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("UPDATE maintenance_cards SET status = 'in_progress' WHERE status = 'ready_for_qa'");
        DB::statement("ALTER TABLE maintenance_cards MODIFY status ENUM(
            'pending','in_progress','waiting_parts','ready','delivered'
        ) NOT NULL DEFAULT 'pending'");
    }
};
