@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="text-primary fw-bold mb-2">Admin Dashboard</h1>
    <p class="text-muted">Manage your school supplies platform</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow stat-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-currency-dollar fs-1"></i>
                </div>
                <h6 class="text-uppercase mb-2">Total Revenue</h6>
                <h3 class="fw-bold mb-0">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <small class="text-white-50">All time</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-bag-check fs-1"></i>
                </div>
                <h6 class="text-uppercase mb-2">Total Orders</h6>
                <h3 class="fw-bold mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                <small class="text-white-50">{{ $stats['pending_orders'] ?? 0 }} pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-hourglass-split fs-1"></i>
                </div>
                <h6 class="text-uppercase mb-2">Pending Products</h6>
                <h3 class="fw-bold mb-0">{{ $stats['pending_products'] ?? 0 }}</h3>
                <small class="text-white-50">Awaiting approval</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-people fs-1"></i>
                </div>
                <h6 class="text-uppercase mb-2">Total Users</h6>
                <h3 class="fw-bold mb-0">{{ $stats['total_users'] ?? 0 }}</h3>
                <small class="text-white-50">{{ $stats['customers'] ?? 0 }} customers, {{ $stats['sellers'] ?? 0 }} sellers</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow stat-card-secondary">
            <div class="card-body text-center">
                <i class="bi bi-person-check text-primary fs-1 mb-2"></i>
                <h6 class="text-primary fw-semibold text-uppercase small mb-2">Customers</h6>
                <h3 class="text-primary mb-0">{{ $stats['customers'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow stat-card-secondary">
            <div class="card-body text-center">
                <i class="bi bi-shop text-primary fs-1 mb-2"></i>
                <h6 class="text-primary fw-semibold text-uppercase small mb-2">Sellers</h6>
                <h3 class="text-primary mb-0">{{ $stats['sellers'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow stat-card-secondary">
            <div class="card-body text-center">
                <i class="bi bi-box-seam text-primary fs-1 mb-2"></i>
                <h6 class="text-primary fw-semibold text-uppercase small mb-2">Total Products</h6>
                <h3 class="text-primary mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

@if($pendingProducts->count() > 0)
    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i>Pending Products
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingProducts as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ $product->seller->business_name ?? $product->seller->user->name }}</td>
                                <td class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to approve this product? It will be visible to customers.">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.reject', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to deny this product? This will deactivate it and notify the seller.">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle me-1"></i>Deny
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

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
                        <th>Order #</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
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
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No orders yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
