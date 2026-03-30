@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
	<div class="col-md-5">
		<div class="card shadow p-4 border-0" style="background-color: #1e2a38;">
			<h3 class="mb-1 fw-bold text-white">Reset Password</h3>
			<p class="text-secondary mb-4" style="font-size:0.9rem;">
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
					<label class="form-label text-secondary">New Password</label>
					<input
						type="password"
						name="password"
						class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
						placeholder="Enter new password"
					/>
					@error('password')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<div class="mb-4">
					<label class="form-label text-secondary">Confirm Password</label>
					<input
						type="password"
						name="password_confirmation"
						class="form-control bg-dark text-white border-secondary @error('password_confirmation') is-invalid @enderror"
						placeholder="Confirm new password"
					/>
					@error('password_confirmation')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>

				<button type="submit" class="btn btn-info w-100 fw-bold" style="color:#fff;">
					Update Password
				</button>
			</form>
		</div>
	</div>
</div>

@endsection
