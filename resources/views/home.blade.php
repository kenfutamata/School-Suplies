@extends('layouts.app')

@section('title', 'Home - School Supplies')

@section('content')
<div class="row mb-4">
  <div class="col-lg-8">
    <div class="p-4 bg-white rounded shadow-sm">
      <h1 class="h3">Top school supplies</h1>
      <p class="text-muted">Find the essential supplies for the new school year.</p>
      <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="/images/hero-1.jpg" class="d-block w-100 rounded" alt="hero">
          </div>
          <div class="carousel-item">
            <img src="/images/hero-2.jpg" class="d-block w-100 rounded" alt="hero">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="p-4 bg-white rounded shadow-sm h-100">
      <h5>Categories</h5>
      <ul class="list-unstyled">
        <li><a href="{{ route('products.index', ['category' => 'Notebooks']) }}">Notebooks</a></li>
        <li><a href="{{ route('products.index', ['category' => 'Pens & Pencils']) }}">Pens & Pencils</a></li>
        <li><a href="{{ route('products.index', ['category' => 'Art Supplies']) }}">Art Supplies</a></li>
        <li><a href="{{ route('products.index', ['category' => 'Bags']) }}">Bags</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 mb-3">
    <h4 class="mb-3">Featured products</h4>
  </div>

  @php
    // Use real products if available, otherwise show sample data
    if(isset($products) && $products->count() > 0) {
      $displayProducts = $products;
    } else {
      // Sample products for preview
      $displayProducts = collect([
        ['id' => null, 'name' => 'Notebook A5', 'price' => 120, 'short' => '80 pages, lined', 'image' => '/images/notebook.jpg', 'stock' => 10],
        ['id' => null, 'name' => 'Ballpen Blue', 'price' => 15, 'short' => 'Smooth ink', 'image' => '/images/pen.jpg', 'stock' => 50],
        ['id' => null, 'name' => 'Crayons 24', 'price' => 220, 'short' => 'Bright colors', 'image' => '/images/crayons.jpg', 'stock' => 5],
        ['id' => null, 'name' => 'School Bag', 'price' => 899, 'short' => 'Durable backpack', 'image' => '/images/bag.jpg', 'stock' => 3],
      ]);
    }
  @endphp

  @foreach($displayProducts as $p)
    <div class="col-6 col-md-3 mb-3">
      @if(isset($p->id))
        <x-product-card :product="['id' => $p->id, 'name' => $p->name, 'price' => $p->price, 'short' => $p->description, 'image' => $p->images->first() ? asset('storage/' . $p->images->first()->image_path) : '/images/placeholder.png', 'stock' => $p->stock]" />
      @else
        @include('components.product-card', ['product' => $p])
      @endif
    </div>
  @endforeach
</div>
@endsection

