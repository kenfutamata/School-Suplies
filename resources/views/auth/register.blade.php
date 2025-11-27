@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="card shadow">
    <div class="card-body p-5">
        <h2 class="text-center mb-4">Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Register as</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="toggleRoleFields()">
                <option value="">Select role...</option>
                <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Customer fields -->
        <div id="customer-fields" style="display: none;">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label for="shipping_address" class="form-label">Shipping Address</label>
                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="2">{{ old('shipping_address') }}</textarea>
            </div>
        </div>

        <!-- Seller fields -->
        <div id="seller-fields" style="display: none;">
            <div class="mb-3">
                <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name') }}">
                @error('business_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tax_id" class="form-label">Tax ID</label>
                <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id') }}">
            </div>
            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
            </div>
            <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
            </div>
            <div class="mb-3">
                <label for="business_address" class="form-label">Business Address</label>
                <textarea class="form-control" id="business_address" name="business_address" rows="2">{{ old('business_address') }}</textarea>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Register</button>
            <a href="{{ route('login') }}" class="btn btn-link">Already registered? Login here</a>
        </div>
    </form>

    <script>
        function toggleRoleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('customer-fields').style.display = role === 'customer' ? 'block' : 'none';
            document.getElementById('seller-fields').style.display = role === 'seller' ? 'block' : 'none';
        }
        // Initialize on page load
        if (document.getElementById('role').value) {
            toggleRoleFields();
        }
    </script>
    </div>
</div>
@endsection
