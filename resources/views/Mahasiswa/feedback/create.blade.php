@extends('Mahasiswa.layout_mahasiswa')

@section('title', 'Beri Feedback Emoji')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Card Header Matkul -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-0 text-dark">{{ $pertemuan->nama_matkul }}</h5>
                    <p class="text-muted small mb-0">{{ $pertemuan->nama_dosen }} • Pertemuan Ke-{{ $pertemuan->pertemuan_ke }}</p>
                </div>
            </div>

            <!-- Card Form Feedback -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4 p-md-5 text-center">
                    <form action="{{ route('mahasiswa.feedback.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_pertemuan" value="{{ $pertemuan->id_pertemuan }}">

                        <div class="mb-5">
                            <h4 class="fw-bold text-dark mb-5">Seberapa paham kamu dengan materi yang disampaikan hari ini?</h4>
                            
                            <!-- Wrapper Emoji -->
                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-5">
                                @php
                                    $feedbackMap = [
                                        1 => ['emoji' => '😶', 'label' => 'Belum Paham'],
                                        2 => ['emoji' => '🤨', 'label' => 'Kurang Paham'],
                                        3 => ['emoji' => '😊', 'label' => 'Cukup Paham'],
                                        4 => ['emoji' => '😄', 'label' => 'Paham'],
                                        5 => ['emoji' => '🤩', 'label' => 'Sangat Paham'],
                                    ];
                                @endphp

                                @foreach($skala as $s)
                                <div class="emoji-option">
                                    <input type="radio" name="id_skala" id="skala_{{ $s->id_skala }}" value="{{ $s->id_skala }}" class="btn-check" required>
                                    
                                    <label class="btn btn-emoji p-0 border-0 shadow-none d-flex flex-column align-items-center" for="skala_{{ $s->id_skala }}">
                                        <span class="emoji-icon display-1 mb-2" style="cursor: pointer; font-size: 4rem;">
                                            {{ $feedbackMap[$s->nilai]['emoji'] ?? '🤔' }}
                                        </span>
                                        <small class="emoji-text fw-bold text-muted p-2 rounded-3" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            {{ $feedbackMap[$s->nilai]['label'] ?? $s->keterangan }}
                                        </small>
                                    </label>
                                </div>
                                @endforeach
                            </div> <!-- Tutup Wrapper Emoji -->
                        </div>

                        <!-- Bagian Tombol (Sekarang di luar wrapper emoji) -->
                        <div class="mt-5">
                            <div class="d-grid gap-2 col-md-6 mx-auto">
                                <button type="submit" class="btn btn-indigo py-3 fw-bold shadow-sm" style="background-color: #4f46e5; color: white; border-radius: 12px; font-size: 1.1rem;">
                                    Kirim Feedback <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-link text-muted text-decoration-none small mt-2">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .emoji-icon {
        transition: all 0.2s ease-in-out;
        opacity: 0.5;
        filter: grayscale(100%); /* Biar kalau belum dipilih warnanya abu-abu */
    }

    .emoji-text {
        transition: all 0.2s;
    }

    .emoji-option:hover .emoji-icon {
        transform: scale(1.2);
        opacity: 1;
        filter: grayscale(0%);
    }

    .btn-check:checked + .btn-emoji .emoji-icon {
        transform: scale(1.3) translateY(-10px);
        opacity: 1;
        filter: grayscale(0%);
        filter: drop-shadow(0 10px 15px rgba(79, 70, 229, 0.3));
    }

    .btn-check:checked + .btn-emoji .emoji-text {
        color: #4f46e5 !important;
        background-color: #eef2ff;
    }
</style>
@endsection