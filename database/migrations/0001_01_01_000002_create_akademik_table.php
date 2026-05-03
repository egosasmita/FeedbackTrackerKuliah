<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id('id_matkul');
            $table->foreignId('id_prodi')->constrained('program_studi', 'id_prodi');
            $table->string('nama_matkul');
            $table->integer('semester');
            $table->integer('sks');
        });

        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->foreignId('id_matkul')->constrained('mata_kuliah', 'id_matkul')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen', 'id_dosen')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->onDelete('cascade');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tahun_ajaran');
            $table->timestamps();
        });

        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id('id_pertemuan');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->string('materi')->nullable();
            $table->date('tanggal');
            $table->enum('status', ['terbuka', 'tertutup'])->default('terbuka');
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa', 'id_mahasiswa')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onDelete('cascade');
            $table->foreignId('id_pertemuan')->constrained('pertemuan', 'id_pertemuan')->onDelete('cascade');
            $table->timestamp('tanggal_input')->useCurrent();
            
            // id_skala dan komentar DIHAPUS dari sini karena pindah ke tabel detail
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('mata_kuliah');
    }
};