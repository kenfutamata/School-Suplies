<x-app-layout>
    <x-slot name="content">
        <div class="container">
            <h1 class="mb-4">Order Details</h1>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order #{{ $order->order_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-3" data-confirm="Are you sure you want to update the order status? This will notify the customer.">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                                <p><strong>Total:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Update Status</label>
                                <select name="status" id="status" class="form-select mb-2" onchange="updateConfirmMessage(this)">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        function updateConfirmMessage(select) {
                            const form = select.closest('form');
                            const status = select.value;
                            let message = 'Are you sure you want to update the order status?';
                            if (status === 'cancelled') {
                                message = 'Are you sure you want to cancel this order? This action cannot be easily undone.';
                            } else if (status === 'delivered') {
                                message = 'Are you sure you want to mark this order as delivered? This will complete the order.';
                            }
                            form.setAttribute('data-confirm', message);
                        }
                    </script>
                </div>
            </div>
            <h3 class="mb-3">Order Items</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-slot>
</x-app-layout>









