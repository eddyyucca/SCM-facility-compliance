<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('laundry_areas')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['washer', 'dryer']);
            $table->decimal('capacity_kg', 8, 2)->comment('Kapasitas mesin dalam KG');
            $table->integer('process_time_minutes')->comment('Waktu proses per kg atau per batch dalam menit');
            $table->enum('status', ['active', 'breakdown'])->default('active');
            $table->decimal('pa_percentage', 5, 2)->default(100.00)->comment('Performance Availability %');
            $table->text('breakdown_notes')->nullable()->comment('Keterangan terakhir breakdown');
            $table->timestamp('breakdown_since')->nullable()->comment('Waktu mulai breakdown terakhir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_equipment');
    }
};
