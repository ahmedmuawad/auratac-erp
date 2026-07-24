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
            if (!Schema::hasColumn('maintenance_cards', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0.00)->after('expected_cost_parts');
            }
            if (!Schema::hasColumn('maintenance_cards', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(15.00)->after('subtotal');
            }
            if (!Schema::hasColumn('maintenance_cards', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0.00)->after('tax_rate');
            }
            if (!Schema::hasColumn('maintenance_cards', 'final_subtotal')) {
                $table->decimal('final_subtotal', 10, 2)->nullable()->after('final_parts_cost');
            }
            if (!Schema::hasColumn('maintenance_cards', 'final_tax_amount')) {
                $table->decimal('final_tax_amount', 10, 2)->nullable()->after('final_subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'tax_rate', 'tax_amount', 'final_subtotal', 'final_tax_amount']);
        });
    }
};
