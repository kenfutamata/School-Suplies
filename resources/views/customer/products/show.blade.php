<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-secondary small mb-1">{{ $product->category ?? 'School Supplies' }}</p>
            <h1 class="fw-bold mb-0">{{ $product->name }}</h1>
        </div>
        <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} text-uppercase">
            {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
        </span>
    </x-slot>

    <div class="py-4">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        @if ($product->images->count() > 0)
                            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    @foreach ($product->images as $idx => $image)
                                        <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner rounded shadow-sm">
                                    @foreach ($product->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>
                                @if ($product->images->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="bg-secondary rounded" style="height: 420px;"></div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Product Description</h5>
                        <p class="text-muted mb-0">{{ $product->description }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Seller</span>
                            <span class="fw-semibold">{{ $product->seller->business_name ?? 'Campus Seller' }}</span>
                        </div>
                        <div class="display-6 text-primary fw-bold mb-3">₱{{ number_format($product->price, 2) }}</div>

                        @auth
                            @if (auth()->user()->isCustomer())
                                <div class="d-flex gap-2 mb-3">
                                    @if ($isWishlisted)
                                        <form action="{{ route('customer.wishlist.destroy', $product) }}" method="POST" data-confirm="Are you sure you want to remove this item from your wishlist?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger w-100" type="submit"><i class="bi bi-heart-fill me-1"></i> Remove from wishlist</button>
                                        </form>
                                    @else
                                        <form action="{{ route('customer.wishlist.store', $product) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-dark w-100" type="submit"><i class="bi bi-heart me-1"></i> Save to wishlist</button>
                                        </form>
                                    @endif
                                </div>

                                <form action="{{ route('customer.cart.store', $product) }}" method="POST">
                                    @csrf
                                    <label for="quantity" class="form-label text-uppercase small text-muted">Quantity</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-123"></i></span>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" {{ $product->stock < 1 ? 'disabled' : '' }} required>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-lg w-100" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                    </button>
                                </form>
                            @endif
                        @else
                            <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to add items to cart or wishlist.</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
