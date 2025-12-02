@extends('layouts.app')

@section('title', 'Admin - Products')

@section('content')
            <h1 class="mb-4">Products Management</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-3">
                <a href="?status=pending" class="btn btn-warning">Pending Approval</a>
                <a href="?status=approved" class="btn btn-success">Approved</a>
                <a href="?status=denied" class="btn btn-danger">Denied</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">All Products</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->seller->business_name }}</td>
                                <td>₱{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @php
                                        $status = $product->status ?? \App\Models\Product::STATUS_PENDING;
                                        $statusColors = [
                                            \App\Models\Product::STATUS_APPROVED => 'success',
                                            \App\Models\Product::STATUS_PENDING => 'warning',
                                            \App\Models\Product::STATUS_REJECTED => 'danger', {{-- FIXED --}}
                                        ];
                                        $badgeClass = $statusColors[$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td>
                                    @if($product->status === \App\Models\Product::STATUS_PENDING)
                                        <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to approve this product? It will be visible to customers.">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.products.reject', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to deny this product? This will deactivate it and notify the seller.">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Deny</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this product? This action cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
@endsection
