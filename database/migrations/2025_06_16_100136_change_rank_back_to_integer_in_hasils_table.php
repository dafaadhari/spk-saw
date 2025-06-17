<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
    {
        Schema::table('hasils', function (Blueprint $table) {
            $table->integer('rank')->change(); // Kembalikan jadi integer
        });
    }

    public function down()
    {
        Schema::table('hasils', function (Blueprint $table) {
            $table->string('rank')->change(); // Kalau di-rollback, jadi string lagi
        });
    }
};
