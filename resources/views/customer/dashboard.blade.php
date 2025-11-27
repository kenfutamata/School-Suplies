<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-primary small mb-1 fw-semibold">Customer Dashboard</p>
            <h1 class="fw-bold mb-0 text-primary">Welcome back, {{ auth()->user()->name }}!</h1>
        </div>
        <span class="badge bg-primary text-uppercase">{{ $orderCount }} Total Orders</span>
    </x-slot>

    <div class="py-4">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-cart3 fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Cart Items</h6>
                        <h2 class="fw-bold mb-0">{{ $cartCount }}</h2>
                        <a href="{{ route('customer.cart.index') }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-right me-1"></i>View Cart
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-heart fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Wishlist</h6>
                        <h2 class="fw-bold mb-0">{{ $wishlistCount }}</h2>
                        <a href="{{ route('customer.wishlist.index') }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-right me-1"></i>View Wishlist
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-bag-check fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Total Orders</h6>
                        <h2 class="fw-bold mb-0">{{ $orderCount }}</h2>
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-right me-1"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-clock-history me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-semibold">#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
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
                                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-bag-x display-4 text-muted d-block mb-3"></i>
                                        <p class="text-muted">No orders yet. Start shopping to see your orders here!</p>
                                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-shop me-2"></i>Browse Products
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
