<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old tables
        Schema::dropIfExists('laundry_breakdowns');
        Schema::dropIfExists('laundry_equipment');

        // Recreate equipment table with new structure (type-based, count-based)
        Schema::create('laundry_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('laundry_areas')->cascadeOnDelete();
            $table->enum('category', ['washer', 'dryer'])->comment('Kategori mesin');
            $table->string('model_name')->comment('Nama tipe/model mesin, cth: Speedqueen 15 kg');
            $table->decimal('capacity_kg', 8, 2)->comment('Kapasitas per unit (kg)');
            $table->unsignedSmallInteger('ready_count')->default(0)->comment('Jumlah unit siap pakai');
            $table->unsignedSmallInteger('breakdown_count')->default(0)->comment('Jumlah unit breakdown');
            $table->text('remarks')->nullable()->comment('Keterangan / notes untuk unit yang breakdown');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_equipment');
        Schema::create('laundry_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('laundry_areas')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['washer', 'dryer']);
            $table->decimal('capacity_kg', 8, 2);
            $table->integer('process_time_minutes');
            $table->enum('status', ['active', 'breakdown'])->default('active');
            $table->decimal('pa_percentage', 5, 2)->default(100.00);
            $table->text('breakdown_notes')->nullable();
            $table->timestamp('breakdown_since')->nullable();
            $table->timestamps();
        });
    }
};
