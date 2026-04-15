<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('open','progress','closed','rejected') NOT NULL DEFAULT 'open'");
    }

    public function down(): void
    {
        DB::statement("UPDATE complaints SET status = 'closed' WHERE status = 'rejected'");
        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('open','progress','closed') NOT NULL DEFAULT 'open'");
    }
};
