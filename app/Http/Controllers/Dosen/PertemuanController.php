<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PertemuanController extends Controller
{
    public function index($id_jadwal)
    {
        $jadwal = DB::table('jadwal')
            ->join('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->where('id_jadwal', $id_jadwal)
            ->first();

        $pertemuan = DB::table('pertemuan')
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        return view('dosen.pertemuan.index', compact('jadwal', 'pertemuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required',
            'pertemuan_ke' => 'required|integer',
            'materi' => 'required',
        ]);

        DB::table('pertemuan')->insert([
            'id_jadwal' => $request->id_jadwal,
            'pertemuan_ke' => $request->pertemuan_ke,
            'materi' => $request->materi,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'terbuka',
            'created_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pertemuan berhasil dibuka!');
    }
}