<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('reporter_wa');
            $table->string('job_title')->nullable()->after('company_name');
            $table->json('photos')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'photos']);
        });
    }
};
