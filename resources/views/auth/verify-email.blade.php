@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Verify Your Email</h2>

        <div class="mb-4 text-muted">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mb-4">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none">Log Out</button>
            </form>
        </div>
    </div>
</div>
@endsection
