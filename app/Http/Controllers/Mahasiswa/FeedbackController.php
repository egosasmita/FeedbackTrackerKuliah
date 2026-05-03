<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    public function create($id_pertemuan)
    {
        $pertemuan = DB::table('pertemuan')
            ->join('jadwal', 'pertemuan.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('dosen', 'jadwal.id_dosen', '=', 'dosen.id_dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id_user')
            ->select('pertemuan.*', 'mata_kuliah.nama_matkul', 'users.nama as nama_dosen')
            ->where('id_pertemuan', $id_pertemuan)
            ->first();

        if (!$pertemuan || $pertemuan->status !== 'terbuka') {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Sesi feedback tidak tersedia.');
        }

        $skala = DB::table('skala_penilaian')->orderBy('nilai', 'asc')->get();

        return view('Mahasiswa.feedback.create', compact('pertemuan', 'skala'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = DB::table('mahasiswa')->where('id_user', $user->id_user)->first();
        
        // Ambil data pertemuan untuk mendapatkan id_jadwal
        $pertemuan = DB::table('pertemuan')->where('id_pertemuan', $request->id_pertemuan)->first();

        // Gunakan updateOrInsert: Jika sudah ada (id_pertemuan & id_mahasiswa sama), maka UPDATE. Jika belum, maka INSERT.
        DB::table('feedback')->updateOrInsert(
            [
                'id_pertemuan' => $request->id_pertemuan,
                'id_mahasiswa' => $mahasiswa->id_mahasiswa
            ],
            [
                'id_jadwal'     => $pertemuan->id_jadwal,
                'id_skala'      => $request->id_skala,
                'komentar'      => '-', 
                'tanggal_input' => now(),
            ]
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Feedback berhasil disimpan!');
    }
}