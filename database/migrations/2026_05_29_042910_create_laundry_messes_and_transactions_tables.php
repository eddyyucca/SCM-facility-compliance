<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_messes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('laundry_areas')->cascadeOnDelete();
            $table->string('name')->comment('Nama Mess');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('laundry_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->comment('Tanggal Transaksi');
            $table->foreignId('mess_id')->constrained('laundry_messes')->cascadeOnDelete();
            
            $table->integer('pob')->default(0)->comment('Jumlah POB Harian');
            
            $table->integer('bag_in')->default(0)->comment('Laundry Bag Masuk');
            $table->decimal('kg_in', 8, 2)->default(0)->comment('Total Kg Masuk');
            
            $table->integer('bag_out')->default(0)->comment('Laundry Bag Keluar');
            $table->decimal('kg_out', 8, 2)->default(0)->comment('Total Kg Keluar');
            
            $table->timestamps();

            // Hanya boleh ada 1 record per tanggal per mess
            $table->unique(['tanggal', 'mess_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_transactions');
        Schema::dropIfExists('laundry_messes');
    }
};
