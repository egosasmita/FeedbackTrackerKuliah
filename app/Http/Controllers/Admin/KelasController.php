<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $f_prodi = $request->input('f_prodi');

        $query = DB::table('kelas')
            ->join('program_studi', 'kelas.id_prodi', '=', 'program_studi.id_prodi')
            ->leftJoin('mahasiswa', 'kelas.id_kelas', '=', 'mahasiswa.id_kelas')
            ->select(
                'kelas.id_kelas', 
                'kelas.nama_kelas', 
                'kelas.id_prodi', 
                'program_studi.nama_prodi',
                DB::raw('count(mahasiswa.id_mahasiswa) as total_mahasiswa')
            );

        if ($search) {
            $query->where('kelas.nama_kelas', 'like', "%{$search}%");
        }

        if ($f_prodi) {
            $query->where('kelas.id_prodi', $f_prodi);
        }

        // PERBAIKAN DISINI: Masukkan semua kolom non-aggregate ke dalam groupBy
        $kelas = $query->groupBy(
                'kelas.id_kelas', 
                'kelas.nama_kelas', 
                'kelas.id_prodi', 
                'program_studi.nama_prodi'
            )
            ->get();

        $prodi = DB::table('program_studi')->get();

        return view('Admin.kelas.index', compact('kelas', 'prodi', 'search', 'f_prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'id_prodi' => 'required'
        ]);

        DB::table('kelas')->insert([
            'nama_kelas' => $request->nama_kelas,
            'id_prodi' => $request->id_prodi,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        DB::table('kelas')->where('id_kelas', $id)->update([
            'nama_kelas' => $request->nama_kelas,
            'id_prodi' => $request->id_prodi,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::table('kelas')->where('id_kelas', $id)->delete();
            return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal hapus! Kelas masih memiliki data mahasiswa.']);
        }
    }
}