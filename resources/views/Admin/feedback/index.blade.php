@extends('Admin.layout_admin')

@section('title', 'Hasil Feedback Mata Kuliah')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold text-dark m-0">Laporan Pemahaman Mahasiswa</h2>
        <p class="text-muted small">Hasil evaluasi tingkat pemahaman per mata kuliah.</p>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.feedback.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="small fw-bold text-muted mb-1">Pilih Program Studi</label>
                    <select name="f_prodi" class="form-select bg-light border-0 py-2">
                        <option value="">Semua Program Studi</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id_prodi }}" {{ $f_prodi == $p->id_prodi ? 'selected' : '' }}>
                                {{ $p->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-white shadow-sm">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small fw-bold">
                        <tr>
                            <th class="border-0 px-4 py-3 text-uppercase">Mata Kuliah</th>
                            <th class="border-0 py-3 text-uppercase">Kelas & Prodi</th>
                            <th class="border-0 text-center py-3 text-uppercase">Responden</th>
                            <th class="border-0 text-center py-3 text-uppercase">Skor Rata-rata</th>
                            <th class="border-0 text-center py-3 text-uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $res)
                        @php
                            $skor = $res->rata_rata_skor ?? 0;
                            $persen = ($skor / 5) * 100;
                            
                            $warnaText = $skor >= 4 ? 'text-success' : ($skor >= 3 ? 'text-warning' : 'text-danger');
                            $warnaBg = $skor >= 4 ? 'bg-success' : ($skor >= 3 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $res->nama_matkul }}</div>
                                <div class="small text-muted">Semester {{ $res->semester }} • {{ $res->sks }} SKS</div>
                            </td>
                            <td>
                                <div class="badge bg-soft-primary text-primary border-0 mb-1" style="background-color: #eef2ff;">
                                    {{ $res->nama_kelas }}
                                </div>
                                <div class="extra-small text-muted" style="font-size: 10px;">{{ $res->nama_prodi }}</div>
                            </td>
                            <td class="text-center font-monospace">{{ $res->total_responden }}</td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <h5 class="m-0 fw-bold {{ $warnaText }}">{{ number_format($skor, 2) }}</h5>
                                    <div class="progress mt-1" style="width: 60px; height: 6px; background-color: #f0f0f0; border-radius: 10px;">
                                        <div class="progress-bar {{ $warnaBg }}" 
                                             role="progressbar" 
                                             style="--lebar: {{ $persen }}%; width: var(--lebar); border-radius: 10px;">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($skor == 0)
                                    <span class="badge rounded-pill bg-secondary px-3">Belum Ada Data</span>
                                @elseif($skor >= 4.5)
                                    <span class="badge rounded-pill bg-success px-3">Sangat Paham</span>
                                @elseif($skor >= 3.5)
                                    <span class="badge rounded-pill bg-info px-3">Paham</span>
                                @elseif($skor >= 2.5)
                                    <span class="badge rounded-pill bg-warning text-dark px-3">Cukup Paham</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3">Kurang Paham</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>
                                Tidak ada data pemahaman mahasiswa ditemukan.
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