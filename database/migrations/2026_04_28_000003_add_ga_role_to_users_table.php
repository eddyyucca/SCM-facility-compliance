<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','ga','receptionist','hk','laundry','hr') NOT NULL DEFAULT 'receptionist'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin','receptionist','hk','laundry','hr') NOT NULL DEFAULT 'receptionist'");
    }
};
