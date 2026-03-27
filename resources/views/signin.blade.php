@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4">
            <h3 class="mb-4 fw-bold">NIC SIGN IN</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form action="/signin" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Enter email"/>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter password"/>
                    @error('password')
                     <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                    <div class="mb-3">
    <label class="form-label">Captcha</label>

    <div class="d-flex align-items-center gap-2">
        <input type="text" id="captchaText"
               class="form-control w-50 text-center fw-bold"
               readonly>

        <button type="button" onclick="loadCaptcha()"
                class="btn btn-secondary btn-sm">
            ↻
        </button>
    </div>

    <input type="text" name="captcha"
           class="form-control mt-2"
           placeholder="Enter captcha">
</div>
                <button type="submit" class="btn btn-dark w-100">Sign In</button>
                <p class="mt-3 text-center">No account? <a href="/signup">Sign Up</a></p>
            </form>
        </div>
    </div>
</div>
<script>
function loadCaptcha() {
    fetch('/captcha')
        .then(res => res.json())
        .then(data => {
            document.getElementById('captchaText').value = data.captcha;
        });
}

// load captcha on page load
window.onload = loadCaptcha;
</script>
@endsection
