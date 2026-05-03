<style>
    #sidebar-wrapper {
        position: fixed; /* Membuat sidebar menempel */
        left: 0;
        top: 0;
        height: 100vh;
        width: 260px;
        background: linear-gradient(180deg, #4B49AC 0%, #3f3da0 100%);
        transition: all 0.3s ease-in-out;
        z-index: 1050;
        border-right: 1px solid rgba(255,255,255,0.05);
        display: flex;
        flex-direction: column;
    }

    .sidebar-heading {
        padding: 2rem 1.5rem;
        font-size: 1.4rem;
        font-weight: 800;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        flex-shrink: 0;
    }

    .sidebar-scroll {
        flex-grow: 1;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Custom Scrollbar Sidebar */
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }

    .list-group-item {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.7);
        padding: 14px 25px;
        margin: 5px 15px;
        border-radius: 10px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        font-weight: 500;
        text-decoration: none;
    }

    .list-group-item:hover {
        background-color: rgba(255,255,255,0.1) !important;
        color: white !important;
        transform: translateX(5px);
    }

    .list-group-item.active {
        background-color: rgba(255,255,255,0.2) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .list-group-item i { width: 30px; font-size: 1.1rem; }
</style>

<div id="sidebar-wrapper" class="shadow-lg">
    <div class="sidebar-heading text-center">
        FT<span class="fw-light text-white-50">ADMIN</span>
    </div>

    <div class="sidebar-scroll">
        <div class="list-group list-group-flush mt-2">
            {{-- DASHBOARD --}}
            <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="px-4 py-2 mt-3"><small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Data Master</small></div>
            
            <a href="{{ route('admin.dosen.index') }}" class="list-group-item {{ request()->is('admin/dosen*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i> Data Dosen
            </a>
            <a href="{{ route('admin.mahasiswa.index') }}" class="list-group-item {{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> Data Mahasiswa
            </a>
            <a href="{{ route('admin.prodi.index') }}" class="list-group-item {{ request()->is('admin/prodi*') ? 'active' : '' }}">
                <i class="fas fa-university"></i> Program Studi
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="list-group-item {{ request()->is('admin/kelas*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i> Data Kelas
            </a>
            
            <div class="px-4 py-2 mt-3"><small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Akademik</small></div>

            <a href="{{ route('admin.matkul.index') }}" class="list-group-item {{ request()->is('admin/matkul*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Mata Kuliah
            </a>
            <a href="{{ route('admin.jadwal.index') }}" class="list-group-item {{ request()->is('admin/jadwal*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Plotting Jadwal
            </a>

            <div class="px-4 py-2 mt-3"><small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Laporan</small></div>
            
            <a href="{{ route('admin.feedback.index') }}" class="list-group-item {{ request()->is('admin/feedback*') ? 'active' : '' }}">
                <i class="fas fa-poll"></i> Hasil Feedback
            </a>

            <form action="{{ route('logout') }}" method="POST" class="mt-4 mb-4">
                @csrf
                <button type="submit" class="list-group-item w-100 text-start border-0" style="background: transparent; cursor: pointer;">
                    <i class="fas fa-sign-out-alt text-danger"></i> <span class="text-danger">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>