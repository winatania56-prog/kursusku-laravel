@extends('layouts.app')

@section('title', 'Lupa Password — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 180px);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">

                <div class="text-center mb-4">
                    <span class="hero-tag d-inline-block mb-2">Pemulihan Akun</span>
                    <h1 class="h2 fw-bold text-dark">Lupa Password?</h1>
                    <p class="text-muted">Masukkan email akun Anda. Kami akan mengirimkan tautan untuk menyetel ulang password.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="nama@email.com"
                                       required
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-true-pink w-100 py-2 shadow-sm mb-3">
                                Kirim Tautan Reset Password
                            </button>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                                    &larr; Ingat password? Masuk di sini
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
