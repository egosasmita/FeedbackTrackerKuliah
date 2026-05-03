<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->foreignId('id_role')->constrained('role', 'id_role');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->timestamps();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_prodi')->constrained('program_studi', 'id_prodi');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas');
            $table->string('nim')->unique();
            $table->integer('angkatan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
        Schema::dropIfExists('users');
    }
};