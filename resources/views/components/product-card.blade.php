@props(['product'])

@php
  // Handle both array and object formats
  $name = is_array($product) ? ($product['name'] ?? 'Product') : ($product->name ?? 'Product');
  $price = is_array($product) ? ($product['price'] ?? 0) : ($product->price ?? 0);
  $short = is_array($product) ? ($product['short'] ?? $product['description'] ?? 'High quality school supply') : ($product->description ?? 'High quality school supply');
  $stock = is_array($product) ? ($product['stock'] ?? 0) : ($product->stock ?? 0);
  $id = is_array($product) ? ($product['id'] ?? null) : ($product->id ?? null);

  // Handle image
  if(is_array($product)) {
    $image = $product['image'] ?? '/images/placeholder.png';
  } else {
    $image = $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '/images/placeholder.png';
  }

  // Determine stock status
  $isOutOfStock = $stock <= 0;
  $isLowStock = $stock > 0 && $stock <= 5;
@endphp

<div class="card h-100 product-card {{ $isOutOfStock ? 'out-of-stock' : '' }}">
  <a href="{{ $id ? route('products.show', $id) : route('products.index') }}" class="text-decoration-none text-reset position-relative d-block">
    <div class="card-img-top ratio ratio-4x3 overflow-hidden bg-light">
      <img src="{{ $image }}"
           alt="{{ $name }}"
           class="object-cover w-100 h-100 product-image"
           style="object-fit: cover;"
           onerror="this.src='/images/placeholder.png'">
    </div>

    {{-- Stock Status Badge --}}
    @if($isOutOfStock)
      <span class="badge bg-danger position-absolute top-0 end-0 m-2">
        Out of Stock
      </span>
    @elseif($isLowStock)
      <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
        <i class="bi bi-exclamation-triangle-fill"></i> Only {{ $stock }} left
      </span>
    @endif
  </a>

  <div class="card-body d-flex flex-column">
    <h5 class="card-title small mb-1">{{ $name }}</h5>
    <p class="card-text text-muted small mb-2 flex-grow-1">{{ Str::limit($short, 50) }}</p>

    <div class="mt-auto">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="fw-bold text-primary">₱{{ number_format($price, 2) }}</div>
        @if(!$isOutOfStock)
          <small class="text-muted">
            @if($isLowStock)
              <i class="bi bi-exclamation-circle text-warning"></i> {{ $stock }} left
            @else
              {{ $stock }} in stock
            @endif
          </small>
        @endif
      </div>

      @if($isOutOfStock)
        <button class="btn btn-sm btn-secondary w-100" disabled>
          <i class="bi bi-x-circle"></i> Out of Stock
        </button>
      @else
        <a href="{{ $id ? route('products.show', $id) : route('products.index') }}"
           class="btn btn-sm btn-primary w-100">
          <i class="bi bi-eye"></i> View Details
        </a>
      @endif
    </div>
  </div>
</div>

<style>
.product-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: 1px solid #dee2e6;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.product-card.out-of-stock {
  opacity: 0.75;
}

.product-card.out-of-stock .product-image {
  filter: grayscale(40%);
}

.product-image {
  transition: filter 0.2s ease, transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

@media (max-width: 576px) {
  .card-img-top {
    height: 150px;
  }
}
</style>
