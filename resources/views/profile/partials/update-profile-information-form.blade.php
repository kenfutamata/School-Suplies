<section class="card border-0 shadow-lg h-100 profile-section">
    <div class="card-header bg-primary text-white border-0 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h5 mb-1 text-white">{{ __('Profile Information') }}</h2>

            <p class="text-white-50 small mb-0">{{ __("Update your account's profile information and email address.") }}</p>
        </div>

        <span class="badge bg-success text-white">{{ ucfirst($user->role ?? 'customer') }}</span>
    </div>

    <div class="card-body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
            @csrf
        </form>

        @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ __('Profile updated successfully.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @elseif (session('status') === 'verification-link-sent')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ __('A new verification link has been sent to your email address.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="post" action="{{ route('profile.update') }}" class="row g-4">
            @csrf
            @method('patch')

            <div class="col-md-6">
                <label for="name" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" class="form-control form-control-lg"
                    value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" class="form-control form-control-lg"
                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-warning mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification" class="btn btn-sm btn-outline-primary">
                        {{ __('Resend verification email') }}
                    </button>
                </div>
                @endif
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Phone') }}</label>
                <input id="phone" name="phone" type="text" class="form-control form-control-lg"
                    value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="shipping_address" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Shipping Address') }}</label>
                <input id="shipping_address" name="shipping_address" type="text" class="form-control form-control-lg"
                    value="{{ old('shipping_address', $user->shipping_address) }}" autocomplete="street-address">
                @error('shipping_address')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if ($user->isSeller())
            <div class="col-12">
                <hr class="border-primary">
                <h3 class="h6 text-uppercase text-primary fw-bold mb-2">
                    <i class="bi bi-shop me-2"></i>{{ __('Seller Profile') }}
                </h3>
                <p class="text-muted small">{{ __('Share details about your business so customers can trust your store.') }}</p>
            </div>

            <div class="col-md-6">
                <label for="business_name" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Business Name') }}</label>
                <input id="business_name" name="business_name" type="text" class="form-control form-control-lg"
                    value="{{ old('business_name', optional($user->seller)->business_name) }}">
                @error('business_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="tax_id" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Tax ID / Registration') }}</label>
                <input id="tax_id" name="tax_id" type="text" class="form-control form-control-lg"
                    value="{{ old('tax_id', optional($user->seller)->tax_id) }}">
                @error('tax_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="contact_email" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Customer Support Email') }}</label>
                <input id="contact_email" name="contact_email" type="email" class="form-control form-control-lg"
                    value="{{ old('contact_email', optional($user->seller)->contact_email) }}">
                @error('contact_email')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="contact_phone" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Customer Support Phone') }}</label>
                <input id="contact_phone" name="contact_phone" type="text" class="form-control form-control-lg"
                    value="{{ old('contact_phone', optional($user->seller)->contact_phone) }}">
                @error('contact_phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="business_address" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Business Address') }}</label>
                <textarea id="business_address" name="business_address" rows="3" class="form-control">{{ old('business_address', optional($user->seller)->business_address) }}</textarea>
                @error('business_address')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top">
                <button type="reset" class="btn btn-outline-primary">{{ __('Reset') }}</button>
                <button class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>{{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</section>