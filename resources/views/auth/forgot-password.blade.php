@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-panel p-4 p-md-5 shadow-sm">
                <h3 class="fw-bold mb-1">Lupa Password?</h3>
                <p class="text-secondary mb-4">Masukkan email akun Anda. Jika terdaftar, Anda akan langsung diarahkan ke halaman untuk mengatur password baru.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Lanjutkan ke Reset Password</button>
                </form>

                <p class="text-center small text-secondary mt-4 mb-0">
                    <a href="{{ route('login') }}">&larr; Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
