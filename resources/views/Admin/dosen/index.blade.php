@extends('Admin.layout_admin')

@section('title', 'Data Dosen')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Data Dosen</h2>
            <p class="text-muted small">Kelola informasi dosen dan penugasan jadwal lintas prodi.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahDosen">
            <i class="fas fa-plus me-2"></i> Tambah Dosen
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.dosen.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">Cari Nama/NIP</label>
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-0" placeholder="Ketik sesuatu..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Filter Prodi</label>
                    <select name="f_prodi" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Prodi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ ($f_prodi ?? '') == $p->id_prodi ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Filter Matkul</label>
                    <select name="f_matkul" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Matkul</option>
                        @foreach($matkul as $m)
                            <option value="{{ $m->id_matkul }}" {{ ($f_matkul ?? '') == $m->id_matkul ? 'selected' : '' }}>{{ $m->nama_matkul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">Filter Kelas</label>
                    <select name="f_kelas" class="form-select form-select-sm bg-light border-0">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ ($f_kelas ?? '') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Terapkan</button>
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-light btn-sm px-3"><i class="fas fa-sync-alt"></i></a>
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
                            <th class="border-0">Dosen & NIP</th>
                            <th class="border-0">Penugasan (Prodi - Kelas - Matkul)</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosen as $index => $d)
                        <tr>
                            <td class="px-3 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($d->nama) }}&background=EBF4FF&color=4B49AC&bold=true" class="rounded-circle me-3" width="45">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $d->nama }}</div>
                                        <div class="text-muted extra-small" style="font-size: 11px;">NIP: {{ $d->nip }} | {{ $d->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    @if($d->penugasan)
                                        @foreach(explode(', ', $d->penugasan) as $tugas)
                                            <span class="badge bg-soft-primary text-primary mb-1" style="background-color: #eef2ff; border-radius: 6px; white-space: normal; text-align: left;">
                                                <i class="fas fa-university me-1"></i> {{ $tugas }}
                                            </span><br>
                                        @endforeach
                                    @else
                                        <span class="text-muted italic small">Belum ada jadwal diplot</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info border-0 me-2" data-bs-toggle="modal" data-bs-target="#modalEditDosen" 
                                        data-id_user="{{ $d->id_user }}" data-nama="{{ $d->nama }}" data-nip="{{ $d->nip }}" data-email="{{ $d->email }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0 btn-delete" data-id="{{ $d->id_user }}" data-nama="{{ $d->nama }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $d->id_user }}" action="{{ route('admin.dosen.destroy', $d->id_user) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahDosen" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Tambah Dosen & Plotting Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.dosen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold">Nama Lengkap</label><input type="text" name="nama" class="form-control bg-light border-0" required></div>
                        <div class="col-md-6"><label class="small fw-bold">NIP</label><input type="text" name="nip" class="form-control bg-light border-0" required></div>
                        <div class="col-md-12"><label class="small fw-bold">Email Kampus</label><input type="email" name="email" class="form-control bg-light border-0" required></div>
                        
                        <div class="col-12"><hr class="my-2 opacity-25"></div>
                        <h6 class="fw-bold text-primary mb-0 small text-uppercase">Plotting Jadwal Pertama</h6>
                        
                        <div class="col-md-4">
                            <label class="small fw-bold">Prodi</label>
                            <select name="id_prodi" id="select-prodi" class="form-select bg-light border-0" required>
                                <option value="">Pilih Prodi...</option>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Mata Kuliah</label>
                            <select name="id_matkul" id="select-matkul" class="form-select bg-light border-0" required disabled>
                                <option value="">Pilih Prodi dulu...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Kelas</label>
                            <select name="id_kelas" id="select-kelas" class="form-select bg-light border-0" required disabled>
                                <option value="">Pilih Prodi dulu...</option>
                            </select>
                        </div>
                        <div class="col-md-12"><label class="small fw-bold">Password Akun</label><input type="password" name="password" class="form-control bg-light border-0" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Simpan Data Dosen</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditDosen" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Edit Profil Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditDosen" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3"><label class="small fw-bold">Nama</label><input type="text" name="nama" id="edit_nama" class="form-control bg-light border-0" required></div>
                    <div class="mb-3"><label class="small fw-bold">NIP</label><input type="text" name="nip" id="edit_nip" class="form-control bg-light border-0" required></div>
                    <div class="mb-3"><label class="small fw-bold">Email</label><input type="email" name="email" id="edit_email" class="form-control bg-light border-0" required></div>
                    <div class="mb-3"><label class="small fw-bold text-danger">Password Baru (Kosongkan jika tidak ganti)</label><input type="password" name="password" class="form-control bg-light border-0"></div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Update Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // AJAX FILTER
    document.getElementById('select-prodi').addEventListener('change', function() {
        const id = this.value;
        const sM = document.getElementById('select-matkul');
        const sK = document.getElementById('select-kelas');
        if (!id) { sM.disabled = true; sK.disabled = true; return; }
        fetch(`/admin/get-data-prodi/${id}`).then(res => res.json()).then(data => {
            sM.innerHTML = '<option value="">Pilih Matkul...</option>';
            data.matkul.forEach(m => sM.innerHTML += `<option value="${m.id_matkul}">${m.nama_matkul}</option>`);
            sK.innerHTML = '<option value="">Pilih Kelas...</option>';
            data.kelas.forEach(k => sK.innerHTML += `<option value="${k.id_kelas}">${k.nama_kelas}</option>`);
            sM.disabled = false; sK.disabled = false;
        });
    });

    // EDIT MODAL POPULATE
    const modalEdit = document.getElementById('modalEditDosen');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        const b = event.relatedTarget;
        document.getElementById('edit_nama').value = b.getAttribute('data-nama');
        document.getElementById('edit_nip').value = b.getAttribute('data-nip');
        document.getElementById('edit_email').value = b.getAttribute('data-email');
        document.getElementById('formEditDosen').action = `/admin/dosen/${b.getAttribute('data-id_user')}`;
    });

    // DELETE SWEETALERT
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({ title: 'Hapus?', text: "Data akan hilang permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#4B49AC' })
            .then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
        });
    });
</script>
@endpush
@endsection