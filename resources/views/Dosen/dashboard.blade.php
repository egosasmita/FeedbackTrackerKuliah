@extends('Dosen.layout_dosen')
@section('title', 'Dashboard Dosen')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold">Selamat Datang, {{ Auth::user()->nama }}</h3>
        <p class="text-muted small">Berikut adalah ringkasan performa mengajar Anda semester ini.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #4B49AC;">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Rata-rata Skor</h6>
                    <h2 class="fw-bold mt-2">{{ $rataSkor ?? '0.0' }} <small class="fs-6 text-muted">/ 5.0</small></h2>
                    <p class="text-success small mb-0"><i class="fas fa-arrow-up"></i> Performa Stabil</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #10b981;">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Mata Kuliah Diampu</h6>
                    <h2 class="fw-bold mt-2">{{ $totalMatkul ?? '0' }}</h2>
                    <p class="text-muted small mb-0">Semester Ganjil 2025/2026</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-left: 5px solid #f59e0b;">
                <div class="card-body">
                    <h6 class="text-muted small text-uppercase fw-bold">Total Responden</h6>
                    <h2 class="fw-bold mt-2">{{ $totalResponden ?? '0' }}</h2>
                    <p class="text-muted small mb-0">Mahasiswa telah mengisi feedback</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <h5 class="fw-bold mb-4">Statistik Feedback Per Mata Kuliah</h5>
        <canvas id="dosenChart" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('dosenChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pemrograman Web', 'Basis Data', 'Jaringan Komputer'],
            datasets: [{
                label: 'Skor Rata-rata',
                data: [4.5, 4.2, 3.8],
                backgroundColor: '#4B49AC',
                borderRadius: 8
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, max: 5 } }
        }
    });
</script>
@endpush
@endsection