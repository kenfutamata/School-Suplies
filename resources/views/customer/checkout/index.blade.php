@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1 class="mb-4">Checkout</h1>
<form action="{{ route('customer.checkout.store') }}" method="POST">
    @csrf
    
    {{-- Hidden inputs for selected items --}}
    @foreach($selectedItemIds as $itemId)
        <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
    @endforeach
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Shipping Address</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Address</label>
                        <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required>{{ auth()->user()->shipping_address ?? old('shipping_address') }}</textarea>
                        <small class="form-text text-muted">Use the map below to pick your address</small>
                    </div>
                    <input type="hidden" id="shipping_latitude" name="shipping_latitude" value="{{ old('shipping_latitude') }}">
                    <input type="hidden" id="shipping_longitude" name="shipping_longitude" value="{{ old('shipping_longitude') }}">
                    <div class="mt-3">
                        <label class="form-label">Payment Method</label>
                        <div>
                            <input type="radio" id="gcash" name="payment_method" value="gcash" {{ old('payment_method') == 'gcash' ? 'checked' : 'checked' }} required>
                            <label for="gcash">GCash</label>
                        </div>
                        <div>
                            <input type="radio" id="card" name="payment_method" value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }} required>
                            <label for="card">Credit/Debit Card</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    @if(isset($itemsBySeller))
                        @foreach($itemsBySeller as $sellerId => $sellerItems)
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-2">
                                    <strong>Seller:</strong> {{ $sellerItems->first()->product->seller->business_name ?? 'Unknown Seller' }}
                                </small>
                                @foreach($sellerItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                    <span class="small">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                                @endforeach
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Subtotal:</small>
                                    <small class="fw-semibold">₱{{ number_format($sellerItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            <span>₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                        @endforeach
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>₱{{ number_format($total, 2) }}</strong>
                    </div>
                    @if(isset($itemsBySeller) && $itemsBySeller->count() > 1)
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> This checkout will create {{ $itemsBySeller->count() }} separate orders (one per seller).
                        </small>
                    @endif
                    <button type="submit" class="btn btn-primary w-100 mt-3">Place Order</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    let map;
    let marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 14.5995,
                lng: 120.9842
            }, // Default to Manila
            zoom: 13
        });

        map.addListener('click', function(e) {
            if (marker) {
                marker.setPosition(e.latLng);
            } else {
                marker = new google.maps.Marker({
                    position: e.latLng,
                    map: map
                });
            }
            document.getElementById('shipping_latitude').value = e.latLng.lat();
            document.getElementById('shipping_longitude').value = e.latLng.lng();
        });
    }
</script>
@endsection