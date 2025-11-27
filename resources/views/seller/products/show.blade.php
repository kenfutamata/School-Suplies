@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="row">
  <div class="col-md-6">
    @if($product->images->count() > 0)
      <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          @foreach($product->images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->name }}">
            </div>
          @endforeach
        </div>
        @if($product->images->count() > 1)
          <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        @endif
      </div>
    @else
      <div class="bg-secondary rounded" style="height: 400px;"></div>
    @endif
  </div>
  
  <div class="col-md-6">
    <div class="bg-white p-4 rounded shadow-sm">
      <h2>{{ $product->name }}</h2>
      <p class="text-muted">Price: ₱{{ number_format($product->price, 2) }}</p>
      <p class="text-muted">Stock: {{ $product->stock }}</p>
      <p class="text-muted">Status: 
        @if($product->is_approved)
          <span class="badge bg-success">Approved</span>
        @else
          <span class="badge bg-warning">Pending Approval</span>
        @endif
      </p>
      <p>{{ $product->description }}</p>
      <div class="d-flex gap-2">
        <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this product? This action cannot be undone.">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>

      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
              <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif
    </div>
  </div>
</div>
@endsection







