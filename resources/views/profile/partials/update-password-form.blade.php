<section class="card border-0 shadow profile-section">
    <div class="card-header bg-primary text-white border-0">
        <h2 class="h5 mb-1 text-white">{{ __('Update Password') }}</h2>
        <p class="text-white-50 small mb-0">{{ __('Use a strong password to keep your account safe.') }}</p>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('password.update') }}" class="d-flex flex-column gap-3" data-confirm="Are you sure you want to change your password? You will need to use the new password for future logins.">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Current Password') }}</label>
                <input id="update_password_current_password" name="current_password" type="password"
                    class="form-control form-control-lg" autocomplete="current-password">
                @if ($errors->updatePassword->has('current_password'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
                @endif
            </div>

            <div>
                <label for="update_password_password" class="form-label text-uppercase small fw-semibold text-primary">{{ __('New Password') }}</label>
                <input id="update_password_password" name="password" type="password"
                    class="form-control form-control-lg" autocomplete="new-password">
                @if ($errors->updatePassword->has('password'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
                @endif
            </div>

            <div>
                <label for="update_password_password_confirmation" class="form-label text-uppercase small fw-semibold text-primary">{{ __('Confirm Password') }}</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="form-control form-control-lg" autocomplete="new-password">
                @if ($errors->updatePassword->has('password_confirmation'))
                    <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                @endif
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2 pt-2">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-key me-2"></i>{{ __('Save New Password') }}
                </button>
            </div>
        </form>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success mt-3 mb-0">
                <i class="bi bi-check-circle me-2"></i>{{ __('Password updated successfully.') }}
            </div>
        @endif
    </div>
</section>
