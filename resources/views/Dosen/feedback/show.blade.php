@extends('Dosen.layout_dosen')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Analisis: {{ $jadwal->nama_matkul }}</h3>
        <form action="" method="GET" class="d-flex">
            <select name="bulan" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>Bulan ke-{{ $i }}</option>
                @endfor
            </select>
        </form>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body">
                    <h6>Rata-rata Skor Bulan Ini</h6>
                    <h2 class="fw-bold">{{ number_format($laporanBulanan, 1) ?? '0' }} / 5.0</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Tren Penilaian Per Pertemuan (Semester Ini)</div>
                <div class="card-body">
                    <canvas id="chartSpesifik" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = JSON.parse('{!! json_encode($trenPertemuan->pluck("pertemuan_ke")) !!}');
    const dataSkor = JSON.parse('{!! json_encode($trenPertemuan->pluck("rata_rata")) !!}');

    new Chart(document.getElementById('chartSpesifik'), {
        type: 'bar', // Gunakan Bar agar lebih jelas per pertemuan
        data: {
            labels: labels.map(l => 'Pertemuan ' + l),
            datasets: [{
                label: 'Skor Mahasiswa',
                data: dataSkor,
                backgroundColor: '#4B49AC'
            }]
        },
        options: { scales: { y: { beginAtZero: true, max: 5 } } }
    });
</script>
@endsection