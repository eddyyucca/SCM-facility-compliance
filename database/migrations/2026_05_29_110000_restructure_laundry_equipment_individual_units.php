<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('laundry_equipment');

        Schema::create('laundry_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('laundry_areas')->cascadeOnDelete();
            $table->enum('category', ['washer', 'dryer']);
            $table->string('model_name')->comment('Nama tipe/brand, cth: Speedqueen');
            $table->decimal('capacity_kg', 8, 2)->comment('Kapasitas per unit per siklus (kg)');
            $table->unsignedSmallInteger('process_time_minutes')->comment('Durasi 1 siklus penuh (menit)');
            $table->unsignedSmallInteger('unit_number')->default(1)->comment('Nomor urut per model per area');
            $table->enum('status', ['ready', 'breakdown'])->default('ready');
            $table->text('remarks')->nullable()->comment('Keterangan breakdown / notes');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_equipment');
    }
};
