<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm" style="border-radius: 0 0 15px 15px;">
    <div class="container-fluid p-0">
        <button class="btn btn-outline-primary btn-sm border-0" id="menu-toggle" type="button" style="border-radius: 8px; padding: 8px 12px;">
            <i class="fas fa-bars fs-5"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            <div class="me-3 text-end d-none d-md-block">
                <span class="d-block fw-bold small text-dark">{{ Auth::user()->nama }}</span>
                <span class="badge bg-soft-primary text-primary extra-small" style="font-size: 10px; background-color: #eef2ff;">Mahasiswa Aktif</span>
            </div>
            
            <div class="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=6366f1&color=fff&bold=true" 
                     class="rounded-circle dropdown-toggle shadow-sm border border-2 border-white" 
                     style="width: 45px; height: 45px; cursor: pointer;" 
                     data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 rounded-3 py-2">
                    <li class="px-3 py-2 fw-bold small text-muted">Profil Mahasiswa</li>
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-graduate me-2 text-primary"></i> Data Diri</a></li>
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-key me-2 text-primary"></i> Ganti Password</a></li>
                    <li><hr class="dropdown-divider border-light"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>