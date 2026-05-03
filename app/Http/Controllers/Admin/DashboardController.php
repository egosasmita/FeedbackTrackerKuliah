<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalMahasiswa = DB::table('mahasiswa')->count();
        $totalDosen     = DB::table('dosen')->count();
        $totalKelas     = DB::table('kelas')->count();
        $totalProdi     = DB::table('program_studi')->count(); // Sesuai nama tabel SQL kamu
        $totalFeedback  = DB::table('feedback')->count();

        // Data Grafik: Distribusi Mahasiswa per Program Studi
        $dataGrafikProdi = DB::table('program_studi')
            ->leftJoin('mahasiswa', 'program_studi.id_prodi', '=', 'mahasiswa.id_prodi')
            ->select('program_studi.nama_prodi', DB::raw('count(mahasiswa.id_mahasiswa) as total'))
            ->groupBy('program_studi.id_prodi', 'program_studi.nama_prodi')
            ->get();

        // Data Grafik: Tren Feedback 7 Hari Terakhir (Real)
        $trenFeedback = DB::table('feedback')
            ->select(DB::raw('DATE(tanggal_input) as tanggal'), DB::raw('count(*) as jumlah'))
            ->where('tanggal_input', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('Admin.dashboard', compact(
            'totalMahasiswa', 
            'totalDosen', 
            'totalKelas', 
            'totalProdi', 
            'totalFeedback',
            'dataGrafikProdi',
            'trenFeedback'
        ));
    }
}