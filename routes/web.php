<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import Controller Utama
use App\Http\Controllers\Auth\LoginController;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MatkulController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

// Import Controller Dosen
use App\Http\Controllers\Dosen\DosenDashboardController;
use App\Http\Controllers\Dosen\JadwalMengajarController;
use App\Http\Controllers\Dosen\PertemuanController;
use App\Http\Controllers\Dosen\FeedbackAnalysisController;

// Import Controller Mahasiswa
use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\FeedbackController as MahasiswaFeedbackController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN UTAMA & AUTH (GUEST)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
    Route::get('/login/dosen', [LoginController::class, 'showDosenLoginForm'])->name('login.dosen');
    Route::get('/login/mahasiswa', [LoginController::class, 'showMahasiswaLoginForm'])->name('login.mahasiswa');
    Route::post('/login/proses', [LoginController::class, 'login'])->name('login');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. RUTE TERPROTEKSI (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /**
     * REDIRECTOR DASHBOARD
     * Mengarahkan user ke dashboard masing-masing berdasarkan role setelah login.
     */
    Route::get('/dashboard', function () {
        $role = Auth::user()->id_role;
        if ($role == 1) return redirect()->route('admin.dashboard');
        if ($role == 2) return redirect()->route('dosen.dashboard');
        if ($role == 3) return redirect()->route('mahasiswa.dashboard');
        abort(403);
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 3. AREA ADMIN (id_role: 1)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:1')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Master Data Dosen
        Route::resource('admin/dosen', DosenController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::get('/admin/get-data-prodi/{id_prodi}', [DosenController::class, 'getDataByProdi']);

        // Master Data Mahasiswa
        Route::resource('admin/mahasiswa', MahasiswaController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);

        // Master Data Akademik (Prodi, Kelas, Matkul)
        Route::resource('admin/prodi', ProdiController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::resource('admin/kelas', KelasController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::resource('admin/matkul', MatkulController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
    
        // Manajemen Jadwal & Monitoring Feedback Global
        Route::resource('admin/jadwal', JadwalController::class, ['as' => 'admin'])->only(['index', 'store', 'destroy']);
        Route::get('/admin/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::get('/admin/feedback/{id}', [AdminFeedbackController::class, 'show'])->name('admin.feedback.show');
    });

    /*
    |--------------------------------------------------------------------------
    | 4. AREA DOSEN (id_role: 2)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:2')->group(function () {
        // Dashboard Dosen & Jadwal Mengajar
        Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');
        Route::get('/dosen/jadwal', [JadwalMengajarController::class, 'index'])->name('dosen.jadwal');
        
        // Manajemen Sesi Pertemuan Kuliah
        Route::get('/dosen/jadwal/{id_jadwal}/pertemuan', [PertemuanController::class, 'index'])->name('dosen.pertemuan.index');
        Route::post('/dosen/pertemuan/store', [PertemuanController::class, 'store'])->name('dosen.pertemuan.store');
        Route::post('/dosen/pertemuan/{id}/update-status', [PertemuanController::class, 'updateStatus'])->name('dosen.pertemuan.updateStatus');

        // Analisis Feedback
        Route::get('/dosen/feedback', [FeedbackAnalysisController::class, 'index'])->name('dosen.feedback.index');
        Route::get('/dosen/feedback/{id_jadwal}', [FeedbackAnalysisController::class, 'show'])->name('dosen.feedback.show');
    });

    /*
    |--------------------------------------------------------------------------
    | 5. AREA MAHASISWA (id_role: 3)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:3')->group(function () {
        // Dashboard
        Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');

        // Pengisian Feedback
        Route::get('/mahasiswa/feedback/{id_pertemuan}', [MahasiswaFeedbackController::class, 'create'])->name('mahasiswa.feedback.create');
        Route::post('/mahasiswa/feedback/store', [MahasiswaFeedbackController::class, 'store'])->name('mahasiswa.feedback.store');
    });

});