<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-primary small mb-1 fw-semibold">Shop School Supplies</p>
            <h1 class="fw-bold mb-0 text-primary">Browse Products</h1>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-clockwise me-1"></i> Reset Filters</a>
    </x-slot>

    <div class="py-4">
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search notebooks, pens, art kits..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="text" name="category" class="form-control" placeholder="Category" value="{{ request('category') }}">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-dark"><i class="bi bi-funnel me-1"></i> Go</button>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse ($products as $product)
                @php
                    $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                @endphp
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 product-card border-0">
                        <div class="position-relative overflow-hidden">
                            @if ($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-gradient" style="height: 220px; background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-box-seam text-primary" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} shadow-sm">
                                    <i class="bi bi-{{ $product->stock > 0 ? 'check-circle' : 'x-circle' }} me-1"></i>{{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-2">
                                @auth
                                    @if (auth()->user()->isCustomer())
                                        @if ($isWishlisted)
                                            <form action="{{ route('customer.wishlist.destroy', $product) }}" method="POST" data-confirm="Remove this item from your wishlist?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                                    <i class="bi bi-heart-fill text-danger"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('customer.wishlist.store', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                                    <i class="bi bi-heart text-dark"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-light btn-sm rounded-circle shadow-sm">
                                        <i class="bi bi-heart text-dark"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2" style="width: fit-content;">
                                <i class="bi bi-tag me-1"></i>{{ $product->category ?? 'School Supplies' }}
                            </span>
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-shop me-1"></i>{{ $product->seller->business_name ?? 'Campus Seller' }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                <div>
                                    <div class="text-muted small text-uppercase">Price</div>
                                    <div class="h5 text-primary mb-0 fw-bold">₱{{ number_format($product->price, 2) }}</div>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-box-seam display-6 d-block mb-2"></i>
                        No products found.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>

