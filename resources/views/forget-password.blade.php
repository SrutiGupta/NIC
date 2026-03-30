@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow p-4">

            <h3 class="mb-1 fw-bold">Forgot Password</h3>
            <p class="text-muted mb-4" style="font-size:0.9rem;">
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
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter your registered email"
                    />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CAPTCHA --}}
                <div class="mb-4">
                    <label class="form-label">Captcha</label>

                    {{-- SVG CAPTCHA --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img
                            src="/captcha"
                            id="captchaImg"
                            alt="captcha"
                            style="height:50px; border-radius:6px; cursor:pointer; border:1px solid #ccc;"
                            onclick="this.src='/captcha?' + Date.now()"
                        />
                        <button type="button"
                                onclick="document.getElementById('captchaImg').src='/captcha?' + Date.now()"
                                class="btn btn-secondary btn-sm">
                            ↻
                        </button>
                    </div>

                    <input
                        type="text"
                        name="captcha"
                        class="form-control @error('captcha') is-invalid @enderror"
                        placeholder="Enter captcha"
                        autocomplete="off"
                    />
                    @error('captcha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark w-100">Send OTP</button>

            </form>

            <div class="text-center mt-3">
                <a href="/signin" style="font-size:0.85rem;">
                    Back to Sign In
                </a>
            </div>

        </div>
    </div>
</div>

@endsection