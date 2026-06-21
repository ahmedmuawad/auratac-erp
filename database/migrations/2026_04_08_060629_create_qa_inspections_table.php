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
        Schema::create('qa_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('qa_supervisor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['passed', 'rejected'])->default('passed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_inspections');
    }
};
