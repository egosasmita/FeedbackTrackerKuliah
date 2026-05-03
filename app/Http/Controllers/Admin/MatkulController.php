<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatkulController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $f_prodi = $request->input('f_prodi');

        $query = DB::table('mata_kuliah')
            ->join('program_studi', 'mata_kuliah.id_prodi', '=', 'program_studi.id_prodi')
            // Pastikan nama kolom di select ini sesuai dengan yang ada di migrasi/DB kamu
            ->select('mata_kuliah.*', 'program_studi.nama_prodi');

        if ($search) {
            $query->where('mata_kuliah.nama_matkul', 'like', "%{$search}%");
            // Jika error lagi di sini, ganti kode_matkul dengan nama kolom kode di DB mu
            $query->orWhere('mata_kuliah.kode_matkul', 'like', "%{$search}%"); 
        }

        if ($f_prodi) {
            $query->where('mata_kuliah.id_prodi', $f_prodi);
        }

        $matkul = $query->get();
        $prodi = DB::table('program_studi')->get();

        return view('Admin.matkul.index', compact('matkul', 'prodi', 'search', 'f_prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matkul' => 'required|unique:mata_kuliah,kode_matkul',
            'nama_matkul' => 'required',
            'sks' => 'required|numeric',
            'semester' => 'required|numeric',
            'id_prodi' => 'required'
        ]);

        DB::table('mata_kuliah')->insert([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'id_prodi' => $request->id_prodi,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        DB::table('mata_kuliah')->where('id_matkul', $id)->update([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'id_prodi' => $request->id_prodi,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Data Mata Kuliah berhasil diubah!');
    }

    public function destroy($id)
    {
        try {
            DB::table('mata_kuliah')->where('id_matkul', $id)->delete();
            return redirect()->back()->with('success', 'Mata Kuliah dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal hapus! Matkul ini sudah masuk dalam jadwal perkuliahan.']);
        }
    }
}