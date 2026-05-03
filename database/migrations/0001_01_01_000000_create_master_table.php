<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('role', function (Blueprint $table) {
            $table->id('id_role');
            $table->string('nama_role');
        });

        Schema::create('program_studi', function (Blueprint $table) {
            $table->id('id_prodi');
            $table->string('nama_prodi');
        });

        Schema::create('skala_penilaian', function (Blueprint $table) {
            $table->id('id_skala');
            $table->integer('nilai');
            $table->string('keterangan');
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_prodi')->constrained('program_studi', 'id_prodi')->onDelete('cascade');
            $table->string('nama_kelas', 50);
            $table->integer('angkatan');
            $table->timestamps();
        });

        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id('id_pertanyaan');
            $table->text('teks_pertanyaan');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('skala_penilaian');
        Schema::dropIfExists('program_studi');
        Schema::dropIfExists('role');
    }
};