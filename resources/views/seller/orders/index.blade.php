@extends('layouts.app')

@section('title', 'Seller - Orders')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
  <h4>My Orders</h4>
  <table class="table">
    <thead>
      <tr>
        <th>Order #</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $order)
        <tr>
          <td>#{{ $order->order_number }}</td>
          <td>{{ $order->user->name }}</td>
          <td>₱{{ number_format($order->total_amount, 2) }}</td>
          <td>
            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
              {{ ucfirst($order->status) }}
            </span>
          </td>
          <td>{{ $order->created_at->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">No orders yet</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{ $orders->links() }}
</div>
@endsection







