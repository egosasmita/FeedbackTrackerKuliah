<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $f_prodi = $request->input('f_prodi');
        $f_kelas = $request->input('f_kelas');

        $query = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id_user')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('kelas', 'mahasiswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('mahasiswa.*', 'users.nama', 'users.email', 'program_studi.nama_prodi', 'kelas.nama_kelas');

        // Search Nama/NIM
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nim', 'like', "%{$search}%");
            });
        }

        // Filter Prodi & Kelas
        if ($f_prodi) $query->where('mahasiswa.id_prodi', $f_prodi);
        if ($f_kelas) $query->where('mahasiswa.id_kelas', $f_kelas);

        $mahasiswa = $query->get();
        $prodi = DB::table('program_studi')->get();
        $kelas = DB::table('kelas')->get();

        return view('Admin.mahasiswa.index', compact('mahasiswa', 'prodi', 'kelas', 'search', 'f_prodi', 'f_kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswa,nim',
            'email' => 'required|email|unique:users,email',
            'id_prodi' => 'required',
            'id_kelas' => 'required',
            'angkatan' => 'required',
            'password' => 'required|min:6',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'id_role' => 3, // Role Mahasiswa
                ]);

                DB::table('mahasiswa')->insert([
                    'id_user' => $user->id_user,
                    'id_prodi' => $request->id_prodi,
                    'id_kelas' => $request->id_kelas,
                    'nim' => $request->nim,
                    'angkatan' => $request->angkatan,
                    'created_at' => now(),
                ]);
            });
            return redirect()->back()->with('success', 'Data Mahasiswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $userData = ['nama' => $request->nama, 'email' => $request->email];
            if ($request->filled('password')) $userData['password'] = Hash::make($request->password);
            
            DB::table('users')->where('id_user', $id)->update($userData);
            DB::table('mahasiswa')->where('id_user', $id)->update([
                'nim' => $request->nim,
                'id_prodi' => $request->id_prodi,
                'id_kelas' => $request->id_kelas,
                'angkatan' => $request->angkatan,
            ]);
        });
        return redirect()->back()->with('success', 'Data Mahasiswa diperbarui!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('mahasiswa')->where('id_user', $id)->delete();
            DB::table('users')->where('id_user', $id)->delete();
        });
        return redirect()->back()->with('success', 'Data Mahasiswa dihapus!');
    }
}