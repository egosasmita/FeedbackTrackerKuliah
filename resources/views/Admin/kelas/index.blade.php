@extends('Admin.layout_admin')

@section('title', 'Data Kelas')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Data Kelas</h2>
            <p class="text-muted small">Manajemen ruang kelas berdasarkan program studi.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
            <i class="fas fa-plus me-2"></i> Tambah Kelas
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.kelas.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="small fw-bold text-muted">Cari Nama Kelas</label>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Contoh: TI-2A..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Filter Prodi</label>
                    <select name="f_prodi" class="form-select bg-light border-0">
                        <option value="">Semua Program Studi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ ($f_prodi ?? '') == $p->id_prodi ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse($kelas as $k)
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box p-3 rounded-3" style="background-color: #f0fdf4;">
                            <i class="fas fa-door-open text-success fa-lg"></i>
                        </div>
                        <span class="badge bg-soft-primary text-primary" style="background-color: #eef2ff; font-size: 10px;">{{ $k->nama_prodi }}</span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $k->nama_kelas }}</h5>
                    <p class="text-muted small mb-0"><i class="fas fa-users me-1"></i> {{ $k->total_mahasiswa }} Mahasiswa Terdaftar</p>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button class="btn btn-sm flex-grow-1 border-0" 
                                style="background-color: #e0f2fe; color: #0369a1; border-radius: 8px;"
                                data-bs-toggle="modal" data-bs-target="#modalEditKelas" 
                                data-id="{{ $k->id_kelas }}" data-nama="{{ $k->nama_kelas }}" data-prodi="{{ $k->id_prodi }}">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                        <button class="btn btn-sm flex-grow-1 border-0 btn-delete" 
                                style="background-color: #fee2e2; color: #b91c1c; border-radius: 8px;"
                                data-id="{{ $k->id_kelas }}" data-nama="{{ $k->nama_kelas }}">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
            <form id="delete-form-{{ $k->id_kelas }}" action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST" style="display: none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">Data Kelas belum tersedia.</div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Tambah Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control bg-light border-0 py-2" placeholder="Contoh: TI-2A" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">Program Studi</label>
                        <select name="id_prodi" class="form-select bg-light border-0 py-2" required>
                            <option value="">Pilih Program Studi...</option>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm" style="background-color: #4B49AC; border:none;">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditKelas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Ubah Data Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditKelas" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="enama_kelas" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2">Program Studi</label>
                        <select name="id_prodi" id="eid_prodi" class="form-select bg-light border-0 py-2" required>
                            @foreach($prodi as $p)
                                <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm" style="background-color: #4B49AC; border:none;">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // POPULATE EDIT DATA
    const modalEdit = document.getElementById('modalEditKelas');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('enama_kelas').value = button.getAttribute('data-nama');
        document.getElementById('eid_prodi').value = button.getAttribute('data-prodi');
        document.getElementById('formEditKelas').action = `/admin/kelas/${button.getAttribute('data-id')}`;
    });

    // SWEETALERT DELETE
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Hapus Kelas ' + nama + '?',
                text: "Data mahasiswa di kelas ini mungkin akan terpengaruh!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4B49AC',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        });
    });
</script>
@endpush
@endsection