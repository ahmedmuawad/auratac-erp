<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->json('repair_requests')->nullable()->after('receiver_id'); // لتخزين نقاط الإصلاح الـ 6
            $table->text('admin_notes')->nullable()->after('repair_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->dropColumn(['repair_requests', 'admin_notes']);
        });
    }
};
