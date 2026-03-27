@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow p-4">
            <h3 class="mb-4 fw-bold">Verify OTP</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!--  TIMER -->
            <div class="text-center mb-3">
                <span id="timer" style="font-weight:bold; color:red;">
                    OTP expires in: 30s
                </span>
            </div>

            <!--  FORM -->
            <form method="POST" action="/verify-otp">
                @csrf

                <input type="text" name="otp" class="form-control mb-3"
                       placeholder="Enter OTP">

                <button type="submit" class="btn btn-dark w-100" id="verifyBtn">
                    Verify
                </button>
            </form>

            <!--  RESEND BUTTON (INSIDE CARD) -->
            <div class="text-center mt-3">
                <button id="resendBtn"
                        onclick="window.location='/resend-otp'"
                        class="btn btn-secondary btn-sm"
                        style="display:none;">
                    Resend OTP
                </button>
            </div>

        </div>
    </div>
</div>

<!--  JS -->
<script>
let timeLeft = 30;

const timerElement = document.getElementById('timer');
const resendBtn = document.getElementById('resendBtn');
const verifyBtn = document.getElementById('verifyBtn');

const countdown = setInterval(() => {
    timeLeft--;

    if (timeLeft > 0) {
        timerElement.innerText = "OTP expires in: " + timeLeft + "s";
    } else {
        timerElement.innerText = " OTP Expired";
        timerElement.style.color = "gray";

        clearInterval(countdown);

        // show resend button
        resendBtn.style.display = "inline-block";

        // disable verify button
        verifyBtn.disabled = true;
    }
}, 1000);
</script>

@endsection