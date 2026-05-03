<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Tambahkan import Model

class LoginController extends Controller
{
    public function showAdminLoginForm() { return view('auth.login_admin'); }
    public function showDosenLoginForm() { return view('auth.login_dosen'); }
    public function showMahasiswaLoginForm() { return view('auth.login_mahasiswa'); }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // 1. Cek Login Admin (Via Email)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Tambahkan pengecekan id_role agar hanya admin yang bisa lewat sini
            if (Auth::attempt(['email' => $login, 'password' => $password, 'id_role' => 1])) {
                $request->session()->regenerate();
                // JANGAN gunakan intended() jika ingin kaku ke dashboard admin
                return redirect()->route('admin.dashboard');
            }
        }

        // 2. Cek Login Mahasiswa (Via NIM)
        $mhs = DB::table('mahasiswa')->where('nim', $login)->first();
        if ($mhs) {
            $user = User::where('id_user', $mhs->id_user)->where('id_role', 3)->first();
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('mahasiswa.dashboard');
            }
        }

        // 3. Cek Login Dosen (Via NIP/NIDN)
        $dosen = DB::table('dosen')->where('nip', $login)->first();
        if ($dosen) {
            $user = User::where('id_user', $dosen->id_user)->where('id_role', 2)->first();
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('dosen.dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}