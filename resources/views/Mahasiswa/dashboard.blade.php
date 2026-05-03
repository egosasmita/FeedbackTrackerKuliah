@extends('Mahasiswa.layout_mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 text-white">
                        <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->nama }}!</h3>
                        <p class="mb-0 opacity-75">Berikan feedback pemahamanmu untuk materi hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 text-dark">Pertemuan Aktif Hari Ini</h5>

    <div class="row">
        @forelse($pertemuanAktif as $p)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                    <span class="badge bg-soft-primary text-primary px-3 py-2" style="background-color: #eef2ff;">P. Ke-{{ $p->pertemuan_ke }}</span>
                    @if($p->sudah_isi)
                        <span class="badge bg-success text-white px-3 py-2"><i class="fas fa-check-circle"></i> Selesai</span>
                    @endif
                </div>
                <div class="card-body px-4">
                    <h5 class="fw-bold text-dark mb-1">{{ $p->nama_matkul }}</h5>
                    <p class="text-muted small mb-3">{{ $p->nama_dosen }}</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <small class="text-muted d-block">Materi:</small>
                        <span class="fw-medium text-dark">{{ $p->materi ?? 'Topik belum diisi' }}</span>
                    </div>

                    @if($p->sudah_isi)
                        <a href="{{ route('mahasiswa.feedback.create', $p->id_pertemuan) }}" class="btn btn-warning w-100 py-2 fw-bold text-white" style="border-radius: 10px;">
                            <i class="fas fa-edit me-2"></i> Edit Feedback
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.feedback.create', $p->id_pertemuan) }}" class="btn btn-indigo w-100 py-2 fw-bold shadow-sm" style="background-color: #4f46e5; color: white; border-radius: 10px;">
                            Isi Feedback <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Tidak ada pertemuan aktif.</h5>
        </div>
        @endforelse
    </div>
</div>
@endsection