<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hr_requests', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('resolved_at');
            $table->text('feedback_text')->nullable()->after('rating');
            $table->timestamp('feedback_at')->nullable()->after('feedback_text');
            $table->boolean('feedback_auto')->default(false)->after('feedback_at');
        });
    }

    public function down(): void
    {
        Schema::table('hr_requests', function (Blueprint $table) {
            $table->dropColumn(['rating', 'feedback_text', 'feedback_at', 'feedback_auto']);
        });
    }
};
