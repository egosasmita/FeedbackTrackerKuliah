@extends('Admin.layout_admin')

@section('title', 'Detail Analisis Feedback')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.feedback.index') }}" class="text-decoration-none small fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Laporan Utama
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Analisis: {{ $jadwal->nama_matkul }}</h3>
        <form action="" method="GET" class="d-flex">
            <select name="bulan" class="form-select shadow-sm" onchange="this.form.submit()" style="border-radius: 10px;">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                        Bulan {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #4B49AC 0%, #7da7fb 100%); border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="opacity-75">Rata-rata Skor Bulan Ini</h6>
                    <h1 class="fw-bold m-0">{{ number_format($laporanBulanan, 1) }} <small style="font-size: 1.5rem;">/ 5.0</small></h1>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white fw-bold border-0 pt-4 px-4">
                    Tren Penilaian Per Pertemuan
                </div>
                <div class="card-body p-4">
                    <canvas id="chartSpesifik" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gunakan JSON.parse dan @json agar tidak merah di VS Code
    const labels = JSON.parse('@json($trenPertemuan->pluck("pertemuan_ke"))');
    const dataSkor = JSON.parse('@json($trenPertemuan->pluck("rata_rata"))');

    new Chart(document.getElementById('chartSpesifik'), {
        type: 'bar',
        data: {
            labels: labels.map(l => 'Pertemuan ' + l),
            datasets: [{
                label: 'Skor Mahasiswa',
                data: dataSkor,
                backgroundColor: '#4B49AC',
                borderRadius: 8, // Membuat bar lebih modern
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 5,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection