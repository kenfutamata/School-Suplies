@extends('layouts.app')

@section('title', 'Product — Notebook A5')

@section('content')
<div class="row">
  <div class="col-md-6">
    @php $images = ['/images/notebook.jpg','/images/notebook-2.jpg']; @endphp
    @include('components.product-carousel', ['images' => $images])
  </div>
  <div class="col-md-6">
    <div class="bg-white p-4 rounded shadow-sm">
      <h2 class="h4">Notebook A5</h2>
      <div class="mb-2">
        <span class="fw-bold h5">₱120.00</span>
        <span class="badge bg-success ms-2">In stock</span>
      </div>
      <p class="text-muted">High quality 80 pages lined notebook. Perfect for note taking and journaling.</p>

      @auth
        @if(auth()->user()->isCustomer())
          <form action="{{ route('products.index') }}" method="GET" class="d-inline">
            <input type="hidden" name="add_to_cart" value="1">
            <div class="mb-3">
              <label class="form-label">Quantity</label>
              <input type="number" class="form-control w-25" name="quantity" value="1" min="1" required>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Add to cart</button>
              <a href="{{ route('customer.checkout.index') }}" class="btn btn-outline-secondary">Buy now</a>
            </div>
          </form>
        @else
          <p class="text-muted">Please <a href="{{ route('login') }}">login as customer</a> to add items to cart.</p>
        @endif
      @else
        <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to add items to cart.</p>
      @endauth

    </div>
  </div>
</div>
@endsection

