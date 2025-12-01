@extends('layouts.app')

@section('title', 'Seller - My Products')

@section('content')
@auth
  @if(auth()->user()->isSeller())
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>My products</h4>
      <a href="{{ route('seller.products.create') }}" class="btn btn-primary">Add product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
      $products = auth()->user()->seller->products()->with('images')->latest()->get();
    @endphp

    <div class="row">
      <div class="col-12">
        <table class="table bg-white rounded shadow-sm">
          <thead>
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $product)
              <tr>
                <td style="width:100px">
                  @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                  @else
                    <div class="bg-secondary" style="width:80px; height:80px;"></div>
                  @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>₱{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                  @php
                    $statusColors = [
                      \App\Models\Product::STATUS_APPROVED => 'success',
                      \App\Models\Product::STATUS_PENDING => 'warning',
                      \App\Models\Product::STATUS_DENIED => 'danger',
                    ];
                    $badge = $statusColors[$product->status] ?? 'secondary';
                  @endphp
                  <span class="badge bg-{{ $badge }}">{{ ucfirst($product->status ?? 'pending') }}</span>
                </td>
                <td>
                  <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this product? This action cannot be undone.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No products yet. <a href="{{ route('seller.products.create') }}">Add your first product</a></td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="alert alert-warning">Please login as a seller to manage products.</div>
  @endif
@else
  <div class="alert alert-info">Please <a href="{{ route('login') }}">login</a> to manage products.</div>
@endauth
@endsection

