<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds a dedicated `reports.view` permission so the Reports/Inquiry sidebar
 * section is permission-gated (previously it was visible to every role).
 * Grants it to the reception role by default; managers get it implicitly.
 */
return new class extends Migration
{
    public function up(): void
    {
        $permId = DB::table('permissions')->where('key', 'reports.view')->value('id');

        if (! $permId) {
            $permId = DB::table('permissions')->insertGetId([
                'key'          => 'reports.view',
                'group'        => 'reports',
                'display_name' => 'التقارير والاستعلام الشامل',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        $receptionId = DB::table('roles')->where('name', 'reception')->value('id');

        if ($receptionId && $permId) {
            $exists = DB::table('permission_role')
                ->where('permission_id', $permId)
                ->where('role_id', $receptionId)
                ->exists();

            if (! $exists) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permId,
                    'role_id'       => $receptionId,
                ]);
            }
        }
    }

    public function down(): void
    {
        $permId = DB::table('permissions')->where('key', 'reports.view')->value('id');

        if ($permId) {
            DB::table('permission_role')->where('permission_id', $permId)->delete();
            DB::table('permissions')->where('id', $permId)->delete();
        }
    }
};
