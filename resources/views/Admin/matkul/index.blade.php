@extends('Admin.layout_admin')

@section('title', 'Mata Kuliah')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Mata Kuliah</h2>
            <p class="text-muted small">Daftar mata kuliah per program studi.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahMatkul">
            <i class="fas fa-plus me-2"></i> Tambah Matkul
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.matkul.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted">Cari Mata Kuliah</label>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Ketik nama atau kode..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Filter Prodi</label>
                    <select name="f_prodi" class="form-select bg-light border-0">
                        <option value="">Semua Prodi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ ($f_prodi ?? '') == $p->id_prodi ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                    <a href="{{ route('admin.matkul.index') }}" class="btn btn-light"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-3">Kode</th>
                            <th class="border-0">Nama Mata Kuliah</th>
                            <th class="border-0">Prodi</th>
                            <th class="border-0 text-center">SKS</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matkul as $m)
                        <tr>
                            <td class="px-3 fw-bold text-primary">
                                {{-- GANTI kode_matkul DISINI JIKA NAMA KOLOM DI DB BERBEDA --}}
                                {{ $m->kode_matkul ?? $m->id_matkul }} 
                            </td>
                            <td class="fw-bold text-dark">{{ $m->nama_matkul }}</td>
                            <td><span class="badge bg-soft-info text-primary" style="background-color: #eef2ff;">{{ $m->nama_prodi }}</span></td>
                            <td class="text-center">{{ $m->sks }} SKS</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info border-0" data-bs-toggle="modal" data-bs-target="#modalEditMatkul" 
                                    data-id="{{ $m->id_matkul }}" 
                                    data-kode="{{ $m->kode_matkul ?? '' }}" 
                                    data-nama="{{ $m->nama_matkul }}" 
                                    data-sks="{{ $m->sks }}" 
                                    data-prodi="{{ $m->id_prodi }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger border-0 btn-delete" data-id="{{ $m->id_matkul }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $m->id_matkul }}" action="{{ route('admin.matkul.destroy', $m->id_matkul) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambahMatkul" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.matkul.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold">Kode Matkul</label>
                            <input type="text" name="kode_matkul" class="form-control bg-light border-0" placeholder="MK001" required>
                        </div>
                        <div class="col-md-8">
                            <label class="small fw-bold">Nama Mata Kuliah</label>
                            <input type="text" name="nama_matkul" class="form-control bg-light border-0" placeholder="Pemrograman Web" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">SKS</label>
                            <input type="number" name="sks" class="form-control bg-light border-0" min="1" max="6" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Semester</label>
                            <input type="number" name="semester" class="form-control bg-light border-0" min="1" max="8" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Program Studi</label>
                            <select name="id_prodi" class="form-select bg-light border-0" required>
                                <option value="">Pilih Prodi...</option>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Simpan Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditMatkul" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Edit Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditMatkul" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="small fw-bold">Kode</label><input type="text" name="kode_matkul" id="ekode" class="form-control bg-light border-0" required></div>
                        <div class="col-md-8"><label class="small fw-bold">Nama</label><input type="text" name="nama_matkul" id="enama" class="form-control bg-light border-0" required></div>
                        <div class="col-md-4"><label class="small fw-bold">SKS</label><input type="number" name="sks" id="esks" class="form-control bg-light border-0" required></div>
                        <div class="col-md-4"><label class="small fw-bold">Semester</label><input type="number" name="semester" id="esmt" class="form-control bg-light border-0" required></div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Prodi</label>
                            <select name="id_prodi" id="eid_prodi" class="form-select bg-light border-0" required>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Update Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // POPULATE EDIT
    const modalEdit = document.getElementById('modalEditMatkul');
    modalEdit.addEventListener('show.bs.modal', function (event) {
        const b = event.relatedTarget;
        document.getElementById('ekode').value = b.getAttribute('data-kode');
        document.getElementById('enama').value = b.getAttribute('data-nama');
        document.getElementById('esks').value = b.getAttribute('data-sks');
        document.getElementById('esmt').value = b.getAttribute('data-smt');
        document.getElementById('eid_prodi').value = b.getAttribute('data-prodi');
        document.getElementById('formEditMatkul').action = `/admin/matkul/${b.getAttribute('data-id')}`;
    });

    // DELETE
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({ title: 'Hapus Matkul?', text: "Data jadwal terkait mungkin akan terpengaruh!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#4B49AC' })
            .then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
        });
    });
</script>
@endpush
@endsection