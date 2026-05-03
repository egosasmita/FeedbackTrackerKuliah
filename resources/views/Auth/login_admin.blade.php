@extends('layouts.auth')

@section('title', 'Login Admin')

@section('additional_style')
<style>
    /* Warna ungu/biru tua khas admin */
    .btn-login { background-color: #4B49AC; color: white; width: 100px; border: none; }
    .btn-login:hover { background-color: #3f3da0; color: white; }
</style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0">Login Admin</h5>
        <span class="badge bg-primary">Administrator</span>
    </div>
    
    <div class="alert alert-warning py-2 small">Masukkan Email & Password Admin</div>

    @if ($errors->has('login'))
        <div class="alert alert-danger py-2 small">
            {{ $errors->first('login') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Email Admin</label>
            <input type="text" name="login" class="form-control bg-light @error('login') is-invalid @enderror" 
                   placeholder="admin@mail.com" value="{{ old('login') }}" required autofocus>
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