<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap input search dari user
        $search = $request->input('search');

        $query = DB::table('dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id_user')
            ->leftJoin('jadwal', 'dosen.id_dosen', '=', 'jadwal.id_dosen')
            ->leftJoin('mata_kuliah', 'jadwal.id_matkul', '=', 'mata_kuliah.id_matkul')
            ->leftJoin('program_studi', 'mata_kuliah.id_prodi', '=', 'program_studi.id_prodi')
            ->leftJoin('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'dosen.id_user',
                'dosen.id_dosen',
                'dosen.nip',
                'users.nama', 
                'users.email',
                DB::raw("GROUP_CONCAT(CONCAT(program_studi.nama_prodi, ' - ', kelas.nama_kelas, ' (', mata_kuliah.nama_matkul, ')') SEPARATOR ', ') as penugasan")
            );

        // LOGIC SEARCH: Cari berdasarkan Nama atau NIP
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                ->orWhere('dosen.nip', 'like', "%{$search}%");
            });
        }

        $dosen = $query->groupBy('dosen.id_user', 'dosen.id_dosen', 'dosen.nip', 'users.nama', 'users.email')
            ->get();

        $prodi = DB::table('program_studi')->get();
        $kelas = DB::table('kelas')->get();
        $matkul = DB::table('mata_kuliah')->get();

        return view('Admin.dosen.index', compact('dosen', 'prodi', 'kelas', 'matkul', 'search'));
    }

    public function getDataByProdi($id_prodi)
    {
        $matkul = DB::table('mata_kuliah')->where('id_prodi', $id_prodi)->get();
        $kelas = DB::table('kelas')->where('id_prodi', $id_prodi)->get();

        return response()->json([
            'matkul' => $matkul,
            'kelas' => $kelas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:dosen,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'id_matkul' => 'required',
            'id_kelas' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'id_role' => 2, 
                ]);

                $id_dosen = DB::table('dosen')->insertGetId([
                    'id_user' => $user->id_user,
                    'nip' => $request->nip,
                    'created_at' => now(), 'updated_at' => now()
                ]);

                DB::table('jadwal')->insert([
                    'id_matkul' => $request->id_matkul,
                    'id_dosen' => $id_dosen,
                    'id_kelas' => $request->id_kelas,
                    'hari' => 'Senin',
                    'jam_mulai' => '08:00:00',
                    'jam_selesai' => '09:40:00',
                    'tahun_ajaran' => '2024/2025',
                ]);
            });

            return redirect()->back()->with('success', 'Dosen & Plotting Jadwal berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|unique:dosen,nip,'.$id.',id_user',
            'email' => 'required|email|unique:users,email,'.$id.',id_user',
        ]);

        DB::transaction(function () use ($request, $id) {
            $userData = ['nama' => $request->nama, 'email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            DB::table('users')->where('id_user', $id)->update($userData);
            DB::table('dosen')->where('id_user', $id)->update(['nip' => $request->nip]);
        });

        return redirect()->back()->with('success', 'Data Dosen berhasil diubah!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $dosen = DB::table('dosen')->where('id_user', $id)->first();
            if ($dosen) {
                DB::table('jadwal')->where('id_dosen', $dosen->id_dosen)->delete();
            }
            DB::table('dosen')->where('id_user', $id)->delete();
            DB::table('users')->where('id_user', $id)->delete();
        });
        return redirect()->back()->with('success', 'Dosen dihapus!');
    }
}