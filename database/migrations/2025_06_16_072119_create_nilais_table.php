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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();

            $table->char('tendik_nik', 16);
            $table->char('kode_kriteria', 35);

            $table->float('value');
            $table->timestamps();

            $table->foreign('tendik_nik')->references('nik')->on('tendiks')->onDelete('cascade');
            $table->foreign('kode_kriteria')->references('kode_kriteria')->on('kriterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
