<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient')->nullable();
            $table->string('type')->default('text');     // text | document
            $table->string('status')->default('failed');  // sent | failed | skipped
            $table->text('summary')->nullable();          // message/caption
            $table->text('response')->nullable();         // reason / api response
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
