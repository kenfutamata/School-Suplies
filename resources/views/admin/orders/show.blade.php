@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="mb-4">
    <h1 class="text-primary fw-bold mb-2">Order Details</h1>
    <p class="text-muted">Order #{{ $order->order_number }}</p>
</div>

<div class="card border-0 shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white">
            <i class="bi bi-receipt me-2"></i>Order Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-uppercase text-muted small mb-3">Customer Information</h6>
                <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-uppercase text-muted small mb-3">Order Details</h6>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Status:</strong> 
                    @php
                        $statusColors = [
                            'delivered' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            'processing' => 'info',
                            'shipped' => 'primary'
                        ];
                        $statusColor = $statusColors[strtolower($order->status)] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                </p>
                @if($order->seller)
                    <p><strong>Seller:</strong> {{ $order->seller->business_name ?? $order->seller->user->name }}</p>
                @endif
                <p><strong>Total Amount:</strong> <span class="fw-bold text-primary fs-5">₱{{ number_format($order->total_amount, 2) }}</span></p>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-uppercase text-muted small mb-2">Shipping Address</h6>
            <p class="mb-0">{{ $order->shipping_address }}</p>
            @if($order->shipping_latitude && $order->shipping_longitude)
                <small class="text-muted">
                    <i class="bi bi-geo-alt"></i> 
                    Coordinates: {{ $order->shipping_latitude }}, {{ $order->shipping_longitude }}
                </small>
            @endif
        </div>

        <hr class="my-4">

        <h6 class="text-uppercase text-muted small mb-3">Order Items</h6>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No items found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection









