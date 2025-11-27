@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
@auth
  @if(auth()->user()->isCustomer())
    @php
      $cartItems = auth()->user()->cartItems()->with('product')->get();
      $total = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
    @endphp
    @if($cartItems->count() > 0)
      <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkoutForm" data-confirm="Are you sure you want to place this order? Total: ₱{{ number_format($total, 2) }}. You will proceed to payment after placing the order.">
        @csrf
        <div class="row">
          <div class="col-md-7">
            <div class="bg-white p-4 rounded shadow-sm mb-3">
              <h5>Shipping address</h5>
              <div class="mb-3">
                <label class="form-label">Full name</label>
                <input type="text" class="form-control" name="full_name" value="{{ auth()->user()->name ?? '' }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Address</label>
                <div class="input-group">
                  <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" placeholder="Enter your address" required>{{ old('shipping_address', auth()->user()->shipping_address ?? '') }}</textarea>
                  <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#mapModal">Pick on map</button>
                </div>
                <input type="hidden" id="shipping_latitude" name="shipping_latitude" value="{{ old('shipping_latitude') }}">
                <input type="hidden" id="shipping_longitude" name="shipping_longitude" value="{{ old('shipping_longitude') }}">
              </div>
            </div>

            <div class="bg-white p-4 rounded shadow-sm">
              <h5>Payment method</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment1" value="gcash" {{ old('payment_method') == 'gcash' ? 'checked' : 'checked' }} required>
                <label class="form-check-label" for="payment1">GCash (sandbox)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment2" value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }} required>
                <label class="form-check-label" for="payment2">Credit / Debit Card (sandbox)</label>
              </div>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary">Place order</button>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="bg-white p-4 rounded shadow-sm">
              <h5>Order summary</h5>
              @foreach($cartItems as $item)
                <div class="d-flex justify-content-between mb-2">
                  <div class="text-truncate me-2">{{ $item->product->name }} x{{ $item->quantity }}</div>
                  <div>₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                </div>
              @endforeach
              <hr>
              <div class="d-flex justify-content-between fw-bold">
                <div>Total</div>
                <div>₱{{ number_format($total, 2) }}</div>
              </div>
            </div>
          </div>
        </div>
      </form>
    @else
      <div class="alert alert-warning">Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a></div>
    @endif
  @else
    <div class="alert alert-warning">Please login as a customer to checkout.</div>
  @endif
@else
  <div class="alert alert-info">Please <a href="{{ route('login') }}">login</a> to checkout.</div>
@endauth

<!-- Map Modal (stub) -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick address on map</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height:600px;">
        <!-- TODO: integrate Keyless Google Maps picker here -->
        <div class="d-flex h-100 align-items-center justify-content-center text-muted">Map integration placeholder — integrate keyless-google-maps here.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="pickAddressBtn">Use this address</button>
      </div>
    </div>
  </div>
</div>

@endsection

