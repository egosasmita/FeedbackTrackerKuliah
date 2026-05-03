@extends('Admin.layout_admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold text-dark m-0">Halo, {{ Auth::user()->nama }}! 👋</h2>
        <p class="text-muted">Ringkasan data Feedback Tracker sesuai database.</p>
    </div>

    <!-- 4 Card Utama -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-4 bg-white shadow-sm border-0 h-100">
                <small class="text-uppercase fw-bold text-muted small">Mahasiswa</small>
                <h2 class="fw-bold m-0 mt-2">{{ number_format($totalMahasiswa) }}</h2>
                <i class="fas fa-user-graduate mt-3 text-primary opacity-50"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 bg-white shadow-sm border-0 h-100">
                <small class="text-uppercase fw-bold text-muted small">Dosen</small>
                <h2 class="fw-bold m-0 mt-2">{{ number_format($totalDosen) }}</h2>
                <i class="fas fa-chalkboard-user mt-3 text-info opacity-50"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 bg-white shadow-sm border-0 h-100">
                <small class="text-uppercase fw-bold text-muted small">Kelas</small>
                <h2 class="fw-bold m-0 mt-2">{{ number_format($totalKelas) }}</h2>
                <i class="fas fa-school mt-3 text-success opacity-50"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 bg-white shadow-sm border-0 h-100">
                <small class="text-uppercase fw-bold text-muted small">Prodi</small>
                <h2 class="fw-bold m-0 mt-2">{{ number_format($totalProdi) }}</h2>
                <i class="fas fa-university mt-3 text-warning opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 bg-white shadow-sm border-0">
                <h5 class="fw-bold mb-4">Tren Feedback (7 Hari Terakhir)</h5>
                <canvas id="feedbackChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 bg-white shadow-sm border-0">
                <h5 class="fw-bold mb-4">Mahasiswa per Prodi</h5>
                <canvas id="prodiChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelFeedback = JSON.parse('@json($trenFeedback->pluck("tanggal"))');
    const dataFeedback  = JSON.parse('@json($trenFeedback->pluck("jumlah"))');
    
    const labelProdi    = JSON.parse('@json($dataGrafikProdi->pluck("nama_prodi"))');
    const dataProdi     = JSON.parse('@json($dataGrafikProdi->pluck("total"))');

    const ctx1 = document.getElementById('feedbackChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labelFeedback, // Pakai variabel
            datasets: [{
                label: 'Feedback',
                data: dataFeedback, // Pakai variabel
                borderColor: '#4B49AC',
                backgroundColor: 'rgba(75, 73, 172, 0.1)',
                fill: true,
                tension: 0.4
            }]
        }
    });

    const ctx2 = document.getElementById('prodiChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: labelProdi, // Pakai variabel
            datasets: [{
                data: dataProdi, // Pakai variabel
                backgroundColor: ['#4B49AC', '#7dd3fc', '#facc15', '#f87171', '#34d399'],
                borderWidth: 0
            }]
        },
        options: { cutout: '70%' }
    });
</script>
@endpush