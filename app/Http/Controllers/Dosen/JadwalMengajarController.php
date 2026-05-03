<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        // 1. Ambil data dosen yang sedang login
        $dosen = DB::table('dosen')->where('id_user', Auth::id())->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // 2. Ambil jadwal dengan join ke mata_kuliah dan kelas
        $jadwal = DB::table('jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'jadwal.*', 
                'mata_kuliah.nama_matkul', 
                'mata_kuliah.sks', 
                'kelas.nama_kelas'
            )
            ->where('jadwal.id_dosen', $dosen->id_dosen)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get();

        return view('dosen.jadwal.index', compact('jadwal'));
    }
}