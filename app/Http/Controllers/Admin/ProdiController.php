<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('program_studi')
            ->leftJoin('kelas', 'program_studi.id_prodi', '=', 'kelas.id_prodi')
            ->leftJoin('mahasiswa', 'program_studi.id_prodi', '=', 'mahasiswa.id_prodi')
            ->select(
                'program_studi.*',
                DB::raw('count(distinct kelas.id_kelas) as total_kelas'),
                DB::raw('count(distinct mahasiswa.id_mahasiswa) as total_mahasiswa')
            );

        if ($search) {
            $query->where('nama_prodi', 'like', "%{$search}%");
        }

        $prodi = $query->groupBy('program_studi.id_prodi', 'program_studi.nama_prodi')->get();

        return view('Admin.prodi.index', compact('prodi', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_prodi' => 'required|unique:program_studi,nama_prodi']);

        DB::table('program_studi')->insert([
            'nama_prodi' => $request->nama_prodi
        ]);

        return redirect()->back()->with('success', 'Program Studi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_prodi' => 'required|unique:program_studi,nama_prodi,'.$id.',id_prodi']);

        DB::table('program_studi')->where('id_prodi', $id)->update([
            'nama_prodi' => $request->nama_prodi
        ]);

        return redirect()->back()->with('success', 'Nama Program Studi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::table('program_studi')->where('id_prodi', $id)->delete();
            return redirect()->back()->with('success', 'Program Studi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal hapus! Data prodi masih digunakan oleh tabel lain (Kelas/Mahasiswa).']);
        }
    }
}