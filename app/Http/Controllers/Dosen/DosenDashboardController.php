<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data dosen berdasarkan id_user yang sedang login
        $dosen = DB::table('dosen')->where('id_user', Auth::id())->first();

        if (!$dosen) {
            return redirect()->route('login')->with('error', 'Profil dosen tidak ditemukan.');
        }

        // 2. Hitung total mata kuliah yang diampu (dari tabel jadwal)
        $totalMatkul = DB::table('jadwal')
            ->where('id_dosen', $dosen->id_dosen)
            ->distinct('id_matkul')
            ->count();

        // 3. Hitung total responden (JOIN feedback ke jadwal)
        $totalResponden = DB::table('feedback')
            ->join('jadwal', 'feedback.id_jadwal', '=', 'jadwal.id_jadwal')
            ->where('jadwal.id_dosen', $dosen->id_dosen)
            ->count();

        // 4. Hitung rata-rata skor feedback (JOIN feedback ke skala_penilaian)
        // Kita join ke skala_penilaian karena nilai angka ada di kolom 'nilai'
        $rataSkor = DB::table('feedback')
            ->join('jadwal', 'feedback.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('skala_penilaian', 'feedback.id_skala', '=', 'skala_penilaian.id_skala')
            ->where('jadwal.id_dosen', $dosen->id_dosen)
            ->avg('skala_penilaian.nilai'); 

        return view('Dosen.dashboard', compact('totalMatkul', 'totalResponden', 'rataSkor'));
    }
}