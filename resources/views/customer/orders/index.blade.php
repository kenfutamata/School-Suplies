<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-primary small mb-1 fw-semibold">My Account</p>
            <h1 class="fw-bold mb-0 text-primary">Order History</h1>
        </div>
        <span class="badge bg-primary text-uppercase">{{ $orders->total() }} Orders</span>
    </x-slot>

    <div class="py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse($orders as $order)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-receipt-cutoff text-primary fs-4"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase">Order Number</div>
                                    <div class="fw-bold">#{{ $order->order_number }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small text-uppercase">Date</div>
                            <div class="fw-semibold">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small text-uppercase">Items</div>
                            <div class="fw-semibold">{{ $order->items->count() }} item(s)</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small text-uppercase">Total Amount</div>
                            <div class="h5 text-primary mb-0">₱{{ number_format($order->total_amount, 2) }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small text-uppercase mb-1">Status</div>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusColor = $statusColors[$order->status] ?? 'secondary';
                                $statusIcons = [
                                    'pending' => 'clock-history',
                                    'processing' => 'gear',
                                    'shipped' => 'truck',
                                    'delivered' => 'check-circle',
                                    'cancelled' => 'x-circle'
                                ];
                                $statusIcon = $statusIcons[$order->status] ?? 'circle';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} text-uppercase">
                                <i class="bi bi-{{ $statusIcon }} me-1"></i>{{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-1 text-end">
                            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i> View
                            </a>
                        </div>
                    </div>
                    
                    @if($order->items->count() > 0)
                        <div class="mt-3 pt-3 border-top">
                            <div class="row g-2">
                                @foreach($order->items->take(3) as $item)
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center gap-2 bg-light rounded p-2">
                                            @if($item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                     class="rounded" style="width: 40px; height: 40px; object-fit: cover;" 
                                                     alt="{{ $item->product->name }}">
                                            @else
                                                <div class="bg-secondary rounded" style="width: 40px; height: 40px;"></div>
                                            @endif
                                            <div>
                                                <div class="small fw-semibold">{{ $item->product->name }}</div>
                                                <div class="text-muted small">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <div class="col-auto">
                                        <div class="bg-light rounded p-2 d-flex align-items-center">
                                            <span class="text-muted small">+{{ $order->items->count() - 3 }} more</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center bg-white border rounded p-5 shadow-sm">
                <div class="mb-4">
                    <i class="bi bi-bag-check display-1 text-muted"></i>
                </div>
                <h4 class="mb-2">No orders yet</h4>
                <p class="text-muted mb-4">Start shopping to see your order history here!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-shop me-2"></i> Browse School Supplies
                </a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
