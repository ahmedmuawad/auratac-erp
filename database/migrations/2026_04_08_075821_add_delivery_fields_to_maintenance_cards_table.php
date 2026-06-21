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
            if (!Schema::hasColumn('maintenance_cards', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable();
            }
            $table->decimal('final_labor_cost', 10, 2)->nullable();
            $table->decimal('final_parts_cost', 10, 2)->nullable();
            $table->decimal('final_total_cost', 10, 2)->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('payment_status')->default('unpaid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_cards', function (Blueprint $table) {
            $table->dropColumn([
                'final_labor_cost', 
                'final_parts_cost', 
                'final_total_cost', 
                'delivery_notes', 
                'payment_status'
            ]);
        });
    }
};
