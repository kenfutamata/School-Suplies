<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-primary small mb-1 fw-semibold">Seller Center</p>
            <h1 class="fw-bold mb-0 text-primary">Seller Dashboard</h1>
        </div>
        <span class="badge bg-primary text-uppercase">{{ $seller->business_name ?? 'New Seller' }}</span>
    </x-slot>

    <div class="py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <h2 class="h5 fw-semibold text-primary mb-0">Overview</h2>
            <div class="btn-group">
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i> Post New Supplies
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-gear me-1"></i> Update Profile
                </a>
            </div>
        </div>

        @if ($seller)
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="bi bi-shop me-2"></i>Business Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center text-md-start">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-primary fw-semibold text-uppercase small mb-2">Seller</h6>
                            <p class="fw-bold mb-1">{{ $seller->user->name }}</p>
                            <small class="text-muted">{{ $seller->user->email }}</small>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-primary fw-semibold text-uppercase small mb-2">Business Name</h6>
                            <p class="fw-bold mb-1">{{ $seller->business_name ?? 'Not set' }}</p>
                            <small class="text-muted">{{ $seller->tax_id ? 'Tax ID: '.$seller->tax_id : 'No tax ID on file' }}</small>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h6 class="text-primary fw-semibold text-uppercase small mb-2">Primary Contact</h6>
                            <p class="fw-bold mb-1">{{ $seller->contact_email ?? 'No contact email' }}</p>
                            <small class="text-muted">{{ $seller->contact_phone ?? 'No contact phone' }}</small>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-primary fw-semibold text-uppercase small mb-2">Business Address</h6>
                            <p class="mb-0">{{ $seller->business_address ?? 'Add an address in your profile' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4 g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Total Products</h6>
                        <h2 class="fw-bold mb-0">{{ $totalProducts }}</h2>
                        <a href="{{ route('seller.products.index') }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-right me-1"></i>Manage Products
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Pending Orders</h6>
                        <h2 class="fw-bold mb-0">{{ $pendingOrders }}</h2>
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-right me-1"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-currency-dollar fs-1"></i>
                        </div>
                        <h6 class="text-uppercase mb-2">Total Revenue</h6>
                        <h2 class="fw-bold mb-0">₱{{ number_format($totalRevenue, 2) }}</h2>
                        <small class="text-white-50">All time</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-list-ul me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer</th>
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
                                    <td>{{ $order->user->name }}</td>
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
                                            $statusColor = $statusColors[strtolower($order->status)] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-primary">
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
    </div>
</x-app-layout>
