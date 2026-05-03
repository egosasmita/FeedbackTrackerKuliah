@extends('Dosen.layout_dosen')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold">{{ $jadwal->nama_matkul }} ({{ $jadwal->nama_kelas }})</h3>
        <p class="text-muted">Kelola sesi pertemuan untuk menerima feedback mahasiswa.</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold">Buka Pertemuan Baru</div>
                <div class="card-body">
                    <form action="{{ route('dosen.pertemuan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                        <div class="mb-3">
                            <label class="small fw-bold">Pertemuan Ke-</label>
                            <input type="number" name="pertemuan_ke" class="form-control" placeholder="Contoh: 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Materi / Topik</label>
                            <textarea name="materi" class="form-control" rows="3" placeholder="Apa yang dibahas hari ini?" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Buka Sesi Feedback</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Pertemuan</th>
                                <th>Materi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pertemuan as $p)
                            <tr>
                                <td class="ps-4 fw-bold">Ke-{{ $p->pertemuan_ke }}</td>
                                <td>{{ $p->materi }}</td>
                                <td>{{ date('d M Y', strtotime($p->tanggal)) }}</td>
                                <td>
                                    <span class="badge {{ $p->status == 'terbuka' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection