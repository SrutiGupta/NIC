@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
	<div class="col-md-5">
		<div class="card shadow p-4">
			<h3 class="mb-1 fw-bold">Reset Password</h3>
			<p class="text-muted mb-4" style="font-size:0.9rem;">
				Enter your new password and confirm it.
			</p>

			@if(session('success'))
				<div class="alert alert-success py-2">{{ session('success') }}</div>
			@endif
			@if(session('error'))
				<div class="alert alert-danger py-2">{{ session('error') }}</div>
			@endif

			<form method="POST" action="/reset-password">
				@csrf

				<div class="mb-3">
					<label class="form-label">New Password</label>
					<input
						type="password"
						name="password"
						class="form-control @error('password') is-invalid @enderror"
						placeholder="Enter new password"
					/>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-4">
					<label class="form-label">Confirm Password</label>
					<input
						type="password"
						name="password_confirmation"
						class="form-control @error('password_confirmation') is-invalid @enderror"
						placeholder="Confirm new password"
					/>
					@error('password_confirmation')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<button type="submit" class="btn btn-dark w-100">
					Update Password
				</button>
			</form>

			<p class="mt-3 text-center"><a href="/signin">Back to Sign In</a></p>
		</div>
	</div>
</div>

@endsection
