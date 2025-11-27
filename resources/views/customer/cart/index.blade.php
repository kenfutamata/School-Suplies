<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-secondary small mb-1">Customer Area</p>
            <h1 class="fw-bold mb-0">Shopping Cart</h1>
        </div>
        <span class="badge text-bg-dark text-uppercase">{{ $cartItems->count() }} Items</span>
    </x-slot>

    <div class="py-4">
        @if ($cartItems->count() > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach ($cartItems as $item)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center">
                                <div class="flex-shrink-0">
                                    @if ($item->product->images->first())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                            class="rounded" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="bg-secondary rounded" style="width: 120px; height: 120px;"></div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <div>
                                            <h5 class="mb-1">{{ $item->product->name }}</h5>
                                            <p class="text-muted mb-2">{{ $item->product->seller->business_name ?? 'School Supply Seller' }}</p>
                                            <span class="h5 text-primary mb-0 d-block">₱{{ number_format($item->product->price, 2) }}</span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted text-uppercase d-block">Subtotal</small>
                                            <span class="h5 mb-0">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                                        <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="d-inline-flex align-items-center gap-2" data-confirm="Update the quantity for this item?">
                                            @csrf
                                            @method('PUT')
                                            <label class="text-uppercase small text-muted mb-0">Qty</label>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                                class="form-control" style="width: 90px;" onchange="this.form.submit()">
                                        </form>
                                        <form action="{{ route('customer.cart.destroy', $item) }}" method="POST" class="ms-auto" data-confirm="Are you sure you want to remove this item from your cart?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-body">
                            <h5 class="mb-3">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Items</span>
                                <span>{{ $cartItems->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong>₱{{ number_format($total, 2) }}</strong>
                            </div>
                            <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary w-100 btn-lg mb-2">
                                Proceed to Checkout
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center bg-white border rounded p-5 shadow-sm">
                <i class="bi bi-cart3 display-4 text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Discover notebooks, pens, art kits and more from our sellers.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary"><i class="bi bi-bag me-1"></i> Shop school supplies</a>
            </div>
        @endif
    </div>
</x-app-layout>







