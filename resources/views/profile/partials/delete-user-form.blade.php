<section class="card border-0 shadow profile-section" style="border-top: 4px solid #ef4444 !important;">
    <div class="card-header bg-danger text-white border-0">
        <h2 class="h5 mb-1 text-white">{{ __('Delete Account') }}</h2>
        <p class="text-white-50 small mb-0">
            {{ __('This action is permanent. Download anything important before you leave.') }}
        </p>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('profile.destroy') }}" data-confirm="{{ __('This will permanently remove your account and all associated data. This action cannot be undone. Are you absolutely sure?') }}" class="d-flex flex-column gap-3">
            @csrf
            @method('delete')

            <div>
                <label for="delete_password" class="form-label text-uppercase small fw-semibold text-danger">{{ __('Confirm with Password') }}</label>
                <input type="password" id="delete_password" name="password" class="form-control form-control-lg" placeholder="{{ __('Password') }}">
                @if ($errors->userDeletion->has('password'))
                    <div class="text-danger small mt-1">{{ $errors->userDeletion->first('password') }}</div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-danger fw-semibold">
                    <i class="bi bi-trash me-1"></i> {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </div>
</section>
