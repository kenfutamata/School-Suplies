<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-primary small mb-1 fw-semibold">Order Details</p>
            <h1 class="fw-bold mb-0 text-primary">Order #{{ $order->order_number }}</h1>
        </div>
        @php
            $statusColors = [
                'pending' => 'warning',
                'processing' => 'info',
                'shipped' => 'primary',
                'delivered' => 'success',
                'cancelled' => 'danger'
            ];
            $statusColor = $statusColors[$order->status] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $statusColor }} text-uppercase">{{ ucfirst($order->status) }}</span>
    </x-slot>

    <div class="py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-0">
                        <h5 class="mb-0 text-primary"><i class="bi bi-box-seam me-2"></i>Order Items</h5>
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center gap-3 mb-4 pb-4 border-bottom">
                                @if($item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                         class="rounded shadow-sm" 
                                         style="width: 100px; height: 100px; object-fit: cover;" 
                                         alt="{{ $item->product->name }}">
                                @else
                                    <div class="bg-secondary rounded" style="width: 100px; height: 100px;"></div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $item->product->seller->business_name ?? 'Campus Seller' }}</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted small">Quantity: <strong>{{ $item->quantity }}</strong></span>
                                        <span class="text-muted small">Price: <strong>₱{{ number_format($item->price, 2) }}</strong></span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted small text-uppercase">Subtotal</div>
                                    <div class="h5 text-primary mb-0">₱{{ number_format($item->subtotal, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-primary bg-opacity-10 border-0">
                        <h5 class="mb-0 text-primary"><i class="bi bi-info-circle me-2"></i>Order Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small text-uppercase mb-1">Order Date</div>
                            <div class="fw-semibold">{{ $order->created_at->format('F d, Y') }}</div>
                            <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small text-uppercase mb-1">Order Status</div>
                            <span class="badge bg-{{ $statusColor }} text-uppercase">{{ ucfirst($order->status) }}</span>
                        </div>

                        @if($order->payment)
                            <div class="mb-3">
                                <div class="text-muted small text-uppercase mb-1">Payment Method</div>
                                <div class="fw-semibold">
                                    <i class="bi bi-credit-card me-1"></i>{{ strtoupper($order->payment->method) }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small text-uppercase mb-1">Payment Status</div>
                                <span class="badge bg-{{ $order->payment->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                                @if($order->payment->reference)
                                    <div class="text-muted small mt-1">Ref: {{ $order->payment->reference }}</div>
                                @endif
                            </div>
                        @endif

                        <div class="mb-3">
                            <div class="text-muted small text-uppercase mb-1">Shipping Address</div>
                            <div class="fw-semibold">{{ $order->shipping_address }}</div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Shipping</span>
                            <span>₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total</span>
                            <span class="h5 text-primary mb-0">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
