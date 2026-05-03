<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = DB::table('mahasiswa')->where('id_user', $user->id_user)->first();

        $pertemuanAktif = DB::table('pertemuan')
            ->join('jadwal', 'pertemuan.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('dosen', 'jadwal.id_dosen', '=', 'dosen.id_dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id_user')
            ->select('pertemuan.*', 'mata_kuliah.nama_matkul', 'users.nama as nama_dosen', 'jadwal.jam_mulai', 'jadwal.jam_selesai')
            ->where('jadwal.id_kelas', $mahasiswa->id_kelas)
            ->where('pertemuan.status', 'terbuka')
            ->get();

        // Cek status feedback untuk setiap kartu
        foreach ($pertemuanAktif as $p) {
            $p->sudah_isi = DB::table('feedback')
                ->where('id_pertemuan', $p->id_pertemuan)
                ->where('id_mahasiswa', $mahasiswa->id_mahasiswa)
                ->exists();
        }

        return view('Mahasiswa.dashboard', compact('pertemuanAktif'));
    }
}