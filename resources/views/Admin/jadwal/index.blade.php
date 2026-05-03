@extends('Admin.layout_admin')

@section('title', 'Plotting Jadwal')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">Plotting Jadwal</h2>
            <p class="text-muted small">Hubungkan Dosen, Mata Kuliah, dan Kelas.</p>
        </div>
        <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <i class="fas fa-calendar-plus me-2"></i> Plot Jadwal Baru
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.jadwal.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted">Filter Program Studi</label>
                    <select name="f_prodi" class="form-select bg-light border-0">
                        <option value="">Semua Prodi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ ($f_prodi ?? '') == $p->id_prodi ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light"><i class="fas fa-sync"></i></a>
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
                            <th class="border-0 px-3 py-3">Waktu</th>
                            <th class="border-0">Mata Kuliah</th>
                            <th class="border-0">Dosen</th>
                            <th class="border-0">Kelas</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $j)
                        <tr>
                            <td class="px-3">
                                <span class="fw-bold text-dark d-block">{{ $j->hari }}</span>
                                <small class="text-muted">{{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $j->nama_matkul }}</div>
                                <small class="text-muted">Kode: {{ $j->display_kode }} | {{ $j->nama_prodi }}</small>
                            </td>
                            <td class="fw-bold text-dark">{{ $j->nama_dosen }}</td>
                            <td><span class="badge bg-soft-success text-success" style="background-color: #f0fdf4;">{{ $j->nama_kelas }}</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger border-0 btn-delete" data-id="{{ $j->id_jadwal }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $j->id_jadwal }}" action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">Jadwal belum tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH (Sama seperti sebelumnya) --}}
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Plotting Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Pilih Prodi</label>
                            <select id="modal-prodi" class="form-select bg-light border-0" required>
                                <option value="">Pilih Prodi...</option>
                                @foreach($prodi as $p) <option value="{{ $p->id_prodi }}">{{ $p->nama_prodi }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Dosen Pengajar</label>
                            <select name="id_dosen" class="form-select bg-light border-0" required>
                                <option value="">Pilih Dosen...</option>
                                @foreach($dosen as $d) <option value="{{ $d->id_dosen }}">{{ $d->nama }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Mata Kuliah</label>
                            <select name="id_matkul" id="modal-matkul" class="form-select bg-light border-0" required disabled>
                                <option value="">Pilih Prodi Dulu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Kelas</label>
                            <select name="id_kelas" id="modal-kelas" class="form-select bg-light border-0" required disabled>
                                <option value="">Pilih Prodi Dulu</option>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="small fw-bold">Hari</label>
                            <select name="hari" class="form-select bg-light border-0"><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option></select>
                        </div>
                        <div class="col-md-4"><label class="small fw-bold">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control bg-light border-0" required></div>
                        <div class="col-md-4"><label class="small fw-bold">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control bg-light border-0" required></div>
                        <div class="col-md-12"><label class="small fw-bold">Tahun Ajaran</label><input type="text" name="tahun_ajaran" class="form-control bg-light border-0" placeholder="2024/2025" required></div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #4B49AC; border:none;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('modal-prodi').addEventListener('change', function() {
        const id = this.value;
        const sM = document.getElementById('modal-matkul');
        const sK = document.getElementById('modal-kelas');
        if(!id) return;
        fetch(`/admin/get-data-prodi/${id}`).then(res => res.json()).then(data => {
            sM.innerHTML = '<option value="">Pilih Matkul...</option>';
            data.matkul.forEach(m => sM.innerHTML += `<option value="${m.id_matkul}">${m.nama_matkul}</option>`);
            sK.innerHTML = '<option value="">Pilih Kelas...</option>';
            data.kelas.forEach(k => sK.innerHTML += `<option value="${k.id_kelas}">${k.nama_kelas}</option>`);
            sM.disabled = false; sK.disabled = false;
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({ title: 'Hapus Plotting?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#4B49AC' })
            .then(r => { if(r.isConfirmed) document.getElementById('delete-form-'+id).submit(); });
        });
    });
</script>
@endpush
@endsection