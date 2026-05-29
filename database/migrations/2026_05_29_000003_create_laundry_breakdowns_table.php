<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('laundry_equipment')->cascadeOnDelete();
            $table->text('notes')->comment('Keterangan/deskripsi kerusakan');
            $table->string('reported_by')->nullable()->comment('Nama pelapor');
            $table->timestamp('breakdown_at')->comment('Waktu mulai breakdown');
            $table->timestamp('resolved_at')->nullable()->comment('Waktu selesai/resolved');
            $table->integer('downtime_minutes')->nullable()->comment('Total downtime dalam menit (diisi saat resolved)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_breakdowns');
    }
};
