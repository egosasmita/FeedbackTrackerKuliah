@extends('Admin.layout_admin')

@section('title', 'Program Studi')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Program Studi</h2>
            <p class="text-muted small">Kelola daftar departemen dan program pendidikan.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahProdi">
            <i class="fas fa-plus me-2"></i> Tambah Prodi
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.prodi.index') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari Nama Program Studi..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                    <a href="{{ route('admin.prodi.index') }}" class="btn btn-light"><i class="fas fa-sync"></i></a>
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
        @forelse($prodi as $p)
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-4 pb-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box p-3 rounded-3" style="background-color: #eef2ff;">
                            <i class="fas fa-university text-primary fa-lg"></i>
                        </div>
                        <span class="badge bg-light text-muted fw-normal" style="font-size: 10px;">ID: #{{ $p->id_prodi }}</span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $p->nama_prodi }}</h5>
                    <div class="mb-4 d-flex gap-3">
                        <div class="small text-muted"><i class="fas fa-door-open me-1"></i> {{ $p->total_kelas }} Kelas</div>
                        <div class="small text-muted"><i class="fas fa-users me-1"></i> {{ $p->total_mahasiswa }} Mahasiswa</div>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 d-flex p-0">
                    <button class="btn btn-link text-info flex-grow-1 py-3 text-decoration-none border-end rounded-0 small fw-bold" 
                            data-bs-toggle="modal" data-bs-target="#modalEditProdi" 
                            data-id="{{ $p->id_prodi }}" data-nama="{{ $p->nama_prodi }}">
                        <i class="fas fa-edit me-1"></i> EDIT
                    </button>
                    <button class="btn btn-link text-danger flex-grow-1 py-3 text-decoration-none rounded-0 small fw-bold btn-delete" 
                            data-id="{{ $p->id_prodi }}" data-nama="{{ $p->nama_prodi }}">
                        <i class="fas fa-trash me-1"></i> HAPUS
                    </button>
                </div>
            </div>
            <form id="delete-form-{{ $p->id_prodi }}" action="{{ route('admin.prodi.destroy', $p->id_prodi) }}" method="POST" style="display: none;">
                @csrf @method('DELETE')
            </form>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">Data Program Studi Kosong.</div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahProdi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Tambah Program Studi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.prodi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="small fw-bold text-muted mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" class="form-control bg-light border-0 py-2" placeholder="Contoh: Teknik Informatika" required>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm" style="background-color: #4B49AC; border:none;">Simpan Prodi</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditProdi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Ubah Nama Prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProdi" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <label class="small fw-bold text-muted mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" id="enama_prodi" class="form-control bg-light border-0 py-2" required>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm" style="background-color: #4B49AC; border:none;">Update Prodi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // POPULATE EDIT DATA
    const modalEdit = document.getElementById('modalEditProdi');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('enama_prodi').value = button.getAttribute('data-nama');
        document.getElementById('formEditProdi').action = `/admin/prodi/${button.getAttribute('data-id')}`;
    });

    // SWEETALERT DELETE
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            Swal.fire({
                title: 'Hapus Prodi?',
                text: "Menghapus " + nama + " akan berdampak pada data kelas dan mahasiswa!",
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