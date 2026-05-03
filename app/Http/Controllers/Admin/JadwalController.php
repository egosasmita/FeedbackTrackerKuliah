<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $f_prodi = $request->input('f_prodi');
        $f_kelas = $request->input('f_kelas');

        $query = DB::table('jadwal')
            ->join('dosen', 'jadwal.id_dosen', '=', 'dosen.id_dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id_user')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->join('program_studi', 'mata_kuliah.id_prodi', '=', 'program_studi.id_prodi');

        // Kita ambil semua kolom dulu, lalu tambahkan alias manual untuk amannya
        $query->select(
            'jadwal.*', 
            'users.nama as nama_dosen', 
            'mata_kuliah.nama_matkul', 
            'kelas.nama_kelas',
            'program_studi.nama_prodi'
        );

        if ($f_prodi) $query->where('mata_kuliah.id_prodi', $f_prodi);
        if ($f_kelas) $query->where('jadwal.id_kelas', $f_kelas);

        $jadwal = $query->orderBy('jadwal.hari')->get();

        // LOGIKA TAMBAHAN: Kita suntikkan display_kode secara manual ke tiap baris
        // Ini agar View tidak akan pernah error lagi
        foreach ($jadwal as $j) {
            $j->display_kode = $j->kode_matkul ?? $j->id_matkul ?? '-';
        }
        
        $prodi = DB::table('program_studi')->get();
        $dosen = DB::table('dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id_user')
            ->select('dosen.id_dosen', 'users.nama')
            ->get();

        return view('Admin.jadwal.index', compact('jadwal', 'prodi', 'dosen', 'f_prodi', 'f_kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dosen' => 'required',
            'id_matkul' => 'required',
            'id_kelas' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tahun_ajaran' => 'required'
        ]);

        DB::table('jadwal')->insert([
            'id_dosen' => $request->id_dosen,
            'id_matkul' => $request->id_matkul,
            'id_kelas' => $request->id_kelas,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tahun_ajaran' => $request->tahun_ajaran,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Jadwal perkuliahan berhasil diplot!');
    }

    public function destroy($id)
    {
        DB::table('jadwal')->where('id_jadwal', $id)->delete();
        return redirect()->back()->with('success', 'Plotting jadwal dihapus!');
    }
}