@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="bg-white p-4 rounded shadow-sm mb-3">
      <h4>Order #{{ $order->order_number }}</h4>
      <p class="text-muted">Customer: {{ $order->user->name }}</p>
      <p class="text-muted">Date: {{ $order->created_at->format('F d, Y h:i A') }}</p>
      
      <h5 class="mt-4">Order Items</h5>
      <table class="table">
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
        <tfoot>
          <tr>
            <th colspan="3">Total</th>
            <th>₱{{ number_format($order->total_amount, 2) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="bg-white p-4 rounded shadow-sm">
      <h5>Shipping Address</h5>
      <p>{{ $order->shipping_address }}</p>
      
      <h5 class="mt-4">Order Status</h5>
      <form action="{{ route('seller.orders.update-status', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="status" class="form-select mb-3" id="statusSelect" onchange="updateConfirmMessage(this)">
          <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
          <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
          <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
          <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Update Status</button>
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
</div>
@endsection







