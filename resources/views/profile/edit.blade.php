<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="text-uppercase text-primary small mb-1 fw-semibold">{{ __('Account Settings') }}</p>
                <h2 class="h3 fw-bold mb-0 text-primary">{{ $user->name }}</h2>
                <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
            <span class="badge bg-primary text-uppercase px-3 py-2">
                {{ __('Role:') }} {{ ucfirst($user->role ?? 'customer') }}
            </span>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-8">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="col-lg-4 d-flex flex-column gap-4">
                @include('profile.partials.update-password-form')
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
