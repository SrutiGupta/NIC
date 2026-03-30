@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow p-4 border-0" style="background-color: #1e2a38;">

            <h3 class="mb-1 fw-bold text-white">Forgot Password</h3>
            <p class="text-secondary mb-4" style="font-size:0.9rem;">
                Enter your registered email and solve the CAPTCHA.<br>
                We'll generate an OTP for verification.
            </p>

            @if(session('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif

            <form action="/forget-password" method="POST">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label text-secondary">
                        <i class="bi bi-envelope me-1"></i>Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter your registered email"
                    />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CAPTCHA --}}
                <div class="mb-4">
                    <label class="form-label text-secondary">
                        <i class="bi bi-shield-check me-1"></i>CAPTCHA
                    </label>

                    {{-- SVG from CaptchaController — same as signin page --}}
                    <div class="mb-2">
                        <img
                            src="/captcha"
                            id="captchaImg"
                            alt="captcha"
                            style="border-radius:6px; cursor:pointer;"
                        />
                        <small class="text-secondary d-block mt-1">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <span
                                style="cursor:pointer; color:#00bfff;"
                                onclick="document.getElementById('captchaImg').src='/captcha?' + Date.now()">
                                Refresh CAPTCHA
                            </span>
                        </small>
                    </div>

                    <input
                        type="text"
                        name="captcha"
                        class="form-control bg-dark text-white border-secondary @error('captcha') is-invalid @enderror"
                        placeholder="Enter the answer"
                        autocomplete="off"
                    />
                    @error('captcha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-info w-100 fw-bold" style="color:#fff;">
                    <i class="bi bi-send me-2"></i>Send OTP
                </button>

            </form>

            <div class="text-center mt-3">
                <a href="/signin" class="text-secondary" style="font-size:0.85rem;">
                    ← Back to Sign In
                </a>
            </div>

        </div>
    </div>
</div>

@endsection