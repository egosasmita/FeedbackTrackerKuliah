@extends('Dosen.layout_dosen')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Jadwal Mengajar</h3>
            <p class="text-muted small">Daftar kelas dan mata kuliah yang Anda ampu semester ini.</p>
        </div>
        <button class="btn btn-primary btn-sm px-3 shadow-sm" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Cetak Jadwal
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted">
                            <th class="ps-4 py-3 text-uppercase small fw-bold" style="width: 150px;">Hari</th>
                            <th class="py-3 text-uppercase small fw-bold">Waktu</th>
                            <th class="py-3 text-uppercase small fw-bold">Mata Kuliah</th>
                            <th class="py-3 text-uppercase small fw-bold text-center">SKS</th>
                            <th class="py-3 text-uppercase small fw-bold">Kelas</th>
                            <th class="py-3 text-uppercase small fw-bold">Tahun Ajaran</th>
                            <th class="py-3 text-uppercase small fw-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $j)
                        <tr>
                            <td class="ps-4">
                                <span class="badge rounded-pill bg-info text-white px-3 py-2 shadow-sm">
                                    <i class="fas fa-calendar-day me-1"></i> {{ $j->hari }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">
                                <i class="far fa-clock text-primary me-1"></i>
                                {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $j->nama_matkul }}</div>
                                <small class="text-muted font-monospace">Kode: MK-{{ $j->id_matkul }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $j->sks }} SKS</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-soft-primary rounded-circle p-2 me-2" style="background-color: #e0f2fe;">
                                        <i class="fas fa-door-open text-primary" style="font-size: 12px;"></i>
                                    </div>
                                    <span class="fw-medium">{{ $j->nama_kelas }}</span>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $j->tahun_ajaran }}</td>
                            <td class="text-center">
                                <a href="{{ route('dosen.pertemuan.index', $j->id_jadwal) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-list-ol me-1"></i> Kelola Pertemuan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/calendar.svg" alt="Empty" style="width: 150px;" class="mb-3">
                                <h6 class="text-muted">Belum ada jadwal mengajar yang ditemukan.</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection