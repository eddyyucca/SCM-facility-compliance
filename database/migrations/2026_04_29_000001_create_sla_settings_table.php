<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_settings', function (Blueprint $table) {
            $table->id();
            $table->string('module', 20);        // 'ga' | 'hr'
            $table->string('type', 50);          // 'receptionist' | 'hk' | 'laundry' | 'hr_request'
            $table->string('priority', 30);      // 'rendah' | 'sedang' | 'tinggi' | 'urgent' | 'normal' | 'penting' | 'mendesak'
            $table->unsignedSmallInteger('hours'); // durasi SLA dalam jam
            $table->timestamps();

            $table->unique(['module', 'type', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_settings');
    }
};
