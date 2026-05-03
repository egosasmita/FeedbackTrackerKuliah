<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackAnalysisController extends Controller
{
    // Menampilkan daftar Mata Kuliah yang diampu
    public function index()
    {
        $dosen = DB::table('dosen')->where('id_user', Auth::id())->first();

        $daftarMatkul = DB::table('jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->select('jadwal.id_jadwal', 'mata_kuliah.nama_matkul', 'kelas.nama_kelas')
            ->where('jadwal.id_dosen', $dosen->id_dosen)
            ->get();

        return view('dosen.feedback.index', compact('daftarMatkul'));
    }

    // Menampilkan Analisis Spesifik per Mata Kuliah
    public function show($id_jadwal, Request $request)
    {
        $dosen = DB::table('dosen')->where('id_user', Auth::id())->first();
        $bulan = $request->get('bulan', date('m')); // Default bulan sekarang

        // 1. Info Jadwal
        $jadwal = DB::table('jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->where('id_jadwal', $id_jadwal)->first();

        // 2. Tren Per Pertemuan (Grafik)
        $trenPertemuan = DB::table('pertemuan')
            ->leftJoin('feedback', 'pertemuan.id_pertemuan', '=', 'feedback.id_pertemuan')
            ->leftJoin('skala_penilaian', 'feedback.id_skala', '=', 'skala_penilaian.id_skala')
            ->select('pertemuan.pertemuan_ke', DB::raw('AVG(skala_penilaian.nilai) as rata_rata'))
            ->where('pertemuan.id_jadwal', $id_jadwal)
            ->groupBy('pertemuan.pertemuan_ke')
            ->orderBy('pertemuan.pertemuan_ke', 'asc')
            ->get();

        // 3. Laporan Bulanan (Rata-rata skor di bulan tertentu)
        $laporanBulanan = DB::table('feedback')
            ->join('pertemuan', 'feedback.id_pertemuan', '=', 'pertemuan.id_pertemuan')
            ->join('skala_penilaian', 'feedback.id_skala', '=', 'skala_penilaian.id_skala')
            ->where('pertemuan.id_jadwal', $id_jadwal)
            ->whereMonth('feedback.tanggal_input', $bulan)
            ->avg('skala_penilaian.nilai');

        return view('dosen.feedback.show', compact('jadwal', 'trenPertemuan', 'laporanBulanan', 'bulan'));
    }
}