@extends('Admin.layout_admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Data Mahasiswa</h2>
            <p class="text-muted small">Kelola data mahasiswa dan penempatan kelas.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahMahasiswa">
            <i class="fas fa-plus me-2"></i> Tambah Mahasiswa
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Cari Nama/NIM</label>
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-0" placeholder="Ketik sesuatu..." value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Filter Prodi</label>
                    <select name="f_prodi" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Prodi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ $f_prodi == $p->id_prodi ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Filter Kelas</label>
                    <select name="f_kelas" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ $f_kelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Cari</button>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light btn-sm"><i class="fas fa-sync"></i></a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-3">No</th>
                            <th class="border-0">Mahasiswa & NIM</th>
                            <th class="border-0">Prodi & Kelas</th>
                            <th class="border-0">Angkatan</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa as $index => $m)
                        <tr>
                            <td class="px-3 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($m->nama) }}&background=EBF4FF&color=4B49AC&bold=true" class="rounded-circle me-3" width="45">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $m->nama }}</div>
                                        <div class="text-muted extra-small" style="font-size: 11px;">{{ $m->nim }} | {{ $m->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border-0">{{ $m->nama_prodi }}</span><br>
                                <small class="text-muted fw-bold">Kelas: {{ $m->nama_kelas }}</small>
                            </td>
                            <td>{{ $m->angkatan }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info border-0" data-bs-toggle="modal" data-bs-target="#modalEditMahasiswa" 
                                    data-id_user="{{ $m->id_user }}" data-nama="{{ $m->nama }}" data-nim="{{ $m->nim }}" 
                                    data-email="{{ $m->email }}" data-prodi="{{ $m->id_prodi }}" data-kelas="{{ $m->id_kelas }}" data-angkatan="{{ $m->angkatan }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger border-0 btn-delete" data-id="{{ $m->id_user }}" data-nama="{{ $m->nama }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $m->id_user }}" action="{{ route('admin.mahasiswa.destroy', $m->id_user) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5">Data Mahasiswa Tidak Ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Tambah Mahasiswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">NIM</label>
                            <input type="text" name="nim" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-8">
                            <label class="small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Angkatan</label>
                            <input type="number" name="angkatan" class="form-control bg-light border-0" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Program Studi</label>
                            <select name="id_prodi" id="m-select-prodi" class="form-select bg-light border-0" required>
                                <option value="">Pilih Prodi...</option>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Kelas</label>
                            <select name="id_kelas" id="m-select-kelas" class="form-select bg-light border-0" required disabled>
                                <option value="">Pilih Prodi Dulu</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="small fw-bold">Password Login</label>
                            <input type="password" name="password" class="form-control bg-light border-0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Daftarkan Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditMahasiswa" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditMahasiswa" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold">Nama</label><input type="text" name="nama" id="enama" class="form-control bg-light border-0" required></div>
                        <div class="col-md-6"><label class="small fw-bold">NIM</label><input type="text" name="nim" id="enim" class="form-control bg-light border-0" required></div>
                        <div class="col-md-6"><label class="small fw-bold">Email</label><input type="email" name="email" id="eemail" class="form-control bg-light border-0" required></div>
                        <div class="col-md-6"><label class="small fw-bold">Angkatan</label><input type="number" name="angkatan" id="eangkatan" class="form-control bg-light border-0" required></div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Prodi</label>
                            <select name="id_prodi" id="edit-select-prodi" class="form-select bg-light border-0" required>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Kelas</label>
                            <select name="id_kelas" id="edit-select-kelas" class="form-select bg-light border-0" required>
                                @foreach($kelas as $k) <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-12"><label class="small fw-bold text-danger">Ganti Password (Kosongkan jika tidak)</label><input type="password" name="password" class="form-control bg-light border-0"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX Filter Prodi -> Kelas (Tambah)
    document.getElementById('m-select-prodi').addEventListener('change', function() {
        const id = this.value;
        const sK = document.getElementById('m-select-kelas');
        if(!id) { sK.disabled = true; return; }
        fetch(`/admin/get-data-prodi/${id}`).then(res => res.json()).then(data => {
            sK.innerHTML = '<option value="">Pilih Kelas...</option>';
            data.kelas.forEach(k => sK.innerHTML += `<option value="${k.id_kelas}">${k.nama_kelas}</option>`);
            sK.disabled = false;
        });
    });

    // Populate Edit Modal
    document.getElementById('modalEditMahasiswa').addEventListener('show.bs.modal', function(e) {
        const b = e.relatedTarget;
        document.getElementById('enama').value = b.getAttribute('data-nama');
        document.getElementById('enim').value = b.getAttribute('data-nim');
        document.getElementById('eemail').value = b.getAttribute('data-email');
        document.getElementById('eangkatan').value = b.getAttribute('data-angkatan');
        document.getElementById('edit-select-prodi').value = b.getAttribute('data-prodi');
        document.getElementById('edit-select-kelas').value = b.getAttribute('data-kelas');
        document.getElementById('formEditMahasiswa').action = `/admin/mahasiswa/${b.getAttribute('data-id_user')}`;
    });

    // Delete
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({ title: 'Hapus Mahasiswa?', text: 'Data tidak bisa dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#4B49AC' })
            .then(r => { if(r.isConfirmed) document.getElementById('delete-form-'+id).submit(); });
        });
    });
</script>
@endpush
@endsection