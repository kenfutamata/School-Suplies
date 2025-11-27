@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
            <h1 class="mb-4">Edit Product</h1>
            <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" data-confirm="Are you sure you want to save these changes? The product information will be updated immediately.">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $product->category) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="variant" class="form-label">Variant</label>
                        <input type="text" class="form-control" id="variant" name="variant" value="{{ old('variant', $product->variant) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Images</label>
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-md-2 mb-2">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" width="100">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Add More Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                    <small class="form-text text-muted">You can upload additional images</small>
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
@endsection



