@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
@auth
  @if(auth()->user()->isCustomer())
    <div class="row">
      <div class="col-md-8">
        <div class="bg-white p-3 rounded shadow-sm">
          <h4>Your cart</h4>
          @php
            $cartItems = auth()->user()->cartItems()->with('product.images')->get();
            $total = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
          @endphp
          @if($cartItems->count() > 0)
            <div class="list-group mb-3">
              @foreach($cartItems as $item)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    @if($item->product->images->first())
                      <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" width="50" class="me-2 rounded" alt="{{ $item->product->name }}">
                    @endif
                    <div>
                      <div class="fw-bold">{{ $item->product->name }}</div>
                      <small class="text-muted">₱{{ number_format($item->product->price, 2) }} x {{ $item->quantity }}</small>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" style="width:60px" class="form-control form-control-sm d-inline-block" onchange="this.form.submit()">
                    </form>
                    <form action="{{ route('customer.cart.destroy', $item) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
            <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary">Proceed to checkout</a>
          @else
            <p class="text-muted text-center py-3">Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue Shopping</a>
          @endif
        </div>
      </div>
      <div class="col-md-4">
        @if($cartItems->count() > 0)
          <div class="bg-white p-3 rounded shadow-sm">
            <h5>Order Summary</h5>
            <div class="d-flex justify-content-between">
              <div>Subtotal</div>
              <div>₱{{ number_format($total, 2) }}</div>
            </div>
            <hr>
            <div class="d-grid">
              <a href="{{ route('customer.checkout.index') }}" class="btn btn-success">Checkout</a>
            </div>
          </div>
        @endif
      </div>
    </div>
  @else
    <div class="alert alert-warning">Please login as a customer to view your cart.</div>
  @endif
@else
  <div class="alert alert-info">Please <a href="{{ route('login') }}">login</a> to view your cart.</div>
@endauth
@endsection

