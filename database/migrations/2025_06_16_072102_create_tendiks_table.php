<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tendiks', function (Blueprint $table) {
            $table->char('nik', 16);
            $table->unsignedBigInteger('user_id');
            $table->string('nama');
            $table->string('unit_kerja');
            $table->string('jenis_pegawai');
            $table->integer('jam_kerja_tahunan');
            $table->decimal('jam_kerja_bulanan', 5, 2);
            $table->timestamps();

            $table->primary('nik'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tendiks');
    }
};
