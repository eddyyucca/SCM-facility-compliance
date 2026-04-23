<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('employee_name');
            $table->string('employee_id')->nullable();
            $table->string('company_name', 150);
            $table->string('department', 120);
            $table->string('position', 120)->nullable();
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('service_type', 120);
            $table->enum('priority', ['normal', 'penting', 'mendesak'])->default('normal');
            $table->string('period')->nullable();
            $table->enum('status', ['open', 'progress', 'closed', 'rejected'])->default('open');
            $table->text('description');
            $table->json('attachments')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('sla_deadline')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'sla_deadline']);
            $table->index(['service_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_requests');
    }
};
