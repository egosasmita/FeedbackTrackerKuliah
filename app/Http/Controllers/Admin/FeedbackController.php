<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input filter prodi dari request GET
        $f_prodi = $request->input('f_prodi');

        // Query utama untuk laporan per Mata Kuliah
        $results = DB::table('mata_kuliah')
            ->join('program_studi', 'mata_kuliah.id_prodi', '=', 'program_studi.id_prodi')
            ->join('jadwal', 'mata_kuliah.id_matkul', '=', 'jadwal.id_matkul')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('feedback', 'jadwal.id_jadwal', '=', 'feedback.id_jadwal')
            ->select(
                'mata_kuliah.nama_matkul',
                'mata_kuliah.semester',
                'mata_kuliah.sks',
                'kelas.nama_kelas',
                'program_studi.nama_prodi',
                'program_studi.id_prodi',
                DB::raw('COUNT(feedback.id_feedback) as total_responden'),
                DB::raw('AVG(feedback.id_skala) as rata_rata_skor')
            )
            // Filter hanya aktif jika user memilih prodi tertentu
            ->when($f_prodi, function ($query, $f_prodi) {
                return $query->where('mata_kuliah.id_prodi', $f_prodi);
            })
            ->groupBy(
                'mata_kuliah.id_matkul', 
                'kelas.id_kelas', 
                'mata_kuliah.nama_matkul', 
                'mata_kuliah.semester', 
                'mata_kuliah.sks', 
                'kelas.nama_kelas', 
                'program_studi.nama_prodi',
                'program_studi.id_prodi'
            )
            ->get();

        // Ambil daftar prodi untuk isi dropdown filter
        $prodi = DB::table('program_studi')->get();

        return view('Admin.feedback.index', compact('results', 'prodi', 'f_prodi'));
    }
    public function show(Request $request, $id)
    {
        $bulan = $request->input('bulan', date('m'));

        // 1. Ambil info jadwal & matkul
        $jadwal = DB::table('jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->where('jadwal.id_jadwal', $id)
            ->first();

        // 2. Rata-rata Skor Bulan Ini
        $laporanBulanan = DB::table('feedback')
            ->where('id_jadwal', $id)
            ->whereMonth('tanggal_input', $bulan)
            ->avg('id_skala') ?? 0;

        // 3. Tren per Pertemuan (Untuk Chart)
        $trenPertemuan = DB::table('pertemuan')
            ->leftJoin('feedback', 'pertemuan.id_pertemuan', '=', 'feedback.id_pertemuan')
            ->select('pertemuan.pertemuan_ke', DB::raw('AVG(feedback.id_skala) as rata_rata'))
            ->where('pertemuan.id_jadwal', $id)
            ->groupBy('pertemuan.id_pertemuan', 'pertemuan.pertemuan_ke')
            ->orderBy('pertemuan.pertemuan_ke', 'asc')
            ->get();

        return view('Admin.feedback.show', compact('jadwal', 'laporanBulanan', 'trenPertemuan', 'bulan'));
    }
}