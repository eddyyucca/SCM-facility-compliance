<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('menu_key', 100)->unique();
            $table->string('menu_label', 150);
            $table->string('menu_section', 50); // ga, hr, admin
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->json('allowed_roles');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_permissions');
    }
};
