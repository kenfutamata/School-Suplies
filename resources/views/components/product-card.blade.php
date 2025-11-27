@props(['product'])
@php
  // Handle both array and object formats
  $name = is_array($product) ? ($product['name'] ?? 'Product') : ($product->name ?? 'Product');
  $price = is_array($product) ? ($product['price'] ?? 0) : ($product->price ?? 0);
  $short = is_array($product) ? ($product['short'] ?? $product['description'] ?? 'High quality school supply') : ($product->description ?? 'High quality school supply');
  $stock = is_array($product) ? ($product['stock'] ?? null) : ($product->stock ?? null);
  $id = is_array($product) ? ($product['id'] ?? null) : ($product->id ?? null);
  
  // Handle image
  if(is_array($product)) {
    $image = $product['image'] ?? '/images/placeholder.png';
  } else {
    $image = $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '/images/placeholder.png';
  }
@endphp
<div class="card h-100 product-card">
  <a href="{{ $id ? route('products.show', $id) : route('products.index') }}" class="text-decoration-none text-reset">
    <div class="card-img-top ratio ratio-4x3 overflow-hidden bg-light">
      <img src="{{ $image }}" alt="{{ $name }}" class="object-cover w-100 h-100" style="object-fit: cover;">
    </div>
  </a>
  <div class="card-body d-flex flex-column">
    <h5 class="card-title small mb-1">{{ $name }}</h5>
    <p class="card-text text-muted small mb-2">{{ Str::limit($short, 50) }}</p>
    <div class="mt-auto d-flex justify-content-between align-items-center">
      <div>
        <div class="fw-bold">₱{{ number_format($price, 2) }}</div>
        @if($stock !== null && $stock <= 5)
          <small class="text-danger">Only {{ $stock }} left</small>
        @endif
      </div>
      <a href="{{ $id ? route('products.show', $id) : route('products.index') }}" class="btn btn-sm btn-primary">View</a>
    </div>
  </div>
</div>

