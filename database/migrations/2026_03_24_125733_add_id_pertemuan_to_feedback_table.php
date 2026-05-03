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

        Schema::create('feedback_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_feedback')->constrained('feedback', 'id_feedback')->onDelete('cascade');
            $table->foreignId('id_pertanyaan')->constrained('pertanyaan', 'id_pertanyaan');
            $table->foreignId('id_skala')->constrained('skala_penilaian', 'id_skala');
            $table->text('komentar_opsional')->nullable(); // Jika ingin ada teks per butir soal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            //
        });
    }
};
