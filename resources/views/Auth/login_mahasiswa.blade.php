@extends('layouts.auth')

@section('title', 'Login Mahasiswa')

@section('additional_style')
<style>
    /* Mengubah warna ke biru muda agar sesuai dengan Welcome Page */
    .btn-login { 
        background-color: #0ea5e9; 
        color: white; 
        width: 100px; 
        border: none; 
    }
    .btn-login:hover { 
        background-color: #0284c7; 
        color: white; 
    }
    .badge-student {
        background-color: #0ea5e9;
    }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0">Login Mahasiswa</h5>
        <span class="badge badge-student text-white">Student</span>
    </div>
    
    <div class="alert alert-info py-2 small" style="background-color: #f0f9ff; border-color: #bae6fd; color: #0369a1;">
        Gunakan NIM dan Password Anda
    </div>

    {{-- Menampilkan pesan error jika login gagal --}}
    @if ($errors->has('login'))
        <div class="alert alert-danger py-2 small">
            {{ $errors->first('login') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small">NIM Mahasiswa</label>
            {{-- PERBAIKAN: name diubah menjadi 'login' dan ditambahkan value old --}}
            <input type="text" name="login" class="form-control bg-light @error('login') is-invalid @enderror" 
                   placeholder="Masukkan NIM Anda" value="{{ old('login') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label small">Password</label>
            <input type="password" name="password" id="password" class="form-control bg-light" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="showPass" onclick="togglePassword()">
            <label class="form-check-label small" for="showPass">Tampilkan Password</label>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-login fw-bold shadow-sm">Login</button>
            <a href="#" class="text-decoration-none small text-muted">Lupa Password?</a>
        </div>
    </form>
@endsection