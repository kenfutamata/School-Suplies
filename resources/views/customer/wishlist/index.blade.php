<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-secondary small mb-1">Customer Area</p>
            <h1 class="fw-bold mb-0">My Wishlist</h1>
        </div>
        <span class="badge text-bg-dark text-uppercase">{{ $wishlists->count() }} Items saved</span>
    </x-slot>

    <div class="py-4">
        <div class="row g-4">
            @forelse ($wishlists as $wishlist)
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        @if ($wishlist->product->images->first())
                            <img src="{{ asset('storage/' . $wishlist->product->images->first()->image_path) }}"
                                class="card-img-top" alt="{{ $wishlist->product->name }}"
                                style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-top" style="height: 200px;"></div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2">{{ $wishlist->product->category ?? 'School Supplies' }}</span>
                            <h5 class="card-title">{{ $wishlist->product->name }}</h5>
                            <p class="text-primary fw-bold">₱{{ number_format($wishlist->product->price, 2) }}</p>
                            <small class="text-muted mb-3">{{ $wishlist->product->seller->business_name ?? 'Campus Seller' }}</small>
                            <div class="mt-auto d-flex flex-column gap-2">
                                <a href="{{ route('products.show', $wishlist->product) }}" class="btn btn-dark btn-sm">
                                    <i class="bi bi-eye me-1"></i> View item
                                </a>
                                <form action="{{ route('customer.wishlist.destroy', $wishlist->product) }}" method="POST" data-confirm="Are you sure you want to remove this item from your wishlist?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="bi bi-x-circle me-1"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center bg-white border rounded p-5 shadow-sm">
                        <i class="bi bi-heart display-4 text-muted mb-3"></i>
                        <h4>No favorites yet</h4>
                        <p class="text-muted">Tap the heart icon on any supply to save it here.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary"><i class="bi bi-shop me-1"></i> Browse products</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>







