<x-app-layout>
    <x-slot name="content">
        <div class="container">
            <h1 class="mb-4">Orders Management</h1>
            <div class="mb-3">
                <a href="?status=pending" class="btn btn-warning">Pending</a>
                <a href="?status=processing" class="btn btn-info">Processing</a>
                <a href="?status=delivered" class="btn btn-success">Delivered</a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">All Orders</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
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
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </x-slot>
</x-app-layout>









