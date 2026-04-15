<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->enum('type', ['receptionist', 'hk', 'laundry']);
            $table->string('reporter_name');
            $table->string('reporter_wa', 20)->nullable();
            $table->string('department')->nullable();
            $table->string('building')->nullable();        // nama bangunan/area
            $table->string('room_number', 20)->nullable(); // nomor kamar (receptionist/laundry)
            $table->string('location')->nullable();        // detail lokasi tambahan (hk)
            $table->string('category')->nullable();
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');
            $table->enum('status', ['open', 'progress', 'closed'])->default('open'); // 3 status
            $table->text('description');
            $table->text('admin_notes')->nullable();
            $table->timestamp('sla_deadline')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
