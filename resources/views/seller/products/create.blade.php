@extends('layouts.app')

@section('title', 'Seller - Create Product')

@section('content')
<div class="bg-white p-4 rounded shadow-sm">
  <h4>Create product</h4>
  <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label class="form-label">Product name</label>
      <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Price</label>
      <input class="form-control @error('price') is-invalid @enderror" name="price" type="number" step="0.01" value="{{ old('price') }}" required>
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Stock</label>
      <input class="form-control @error('stock') is-invalid @enderror" name="stock" type="number" value="{{ old('stock') }}" required>
      @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- ⭐ Category dropdown with “Other” input --}}
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select class="form-control" name="category" id="categorySelect">
          <option value="">Select category</option>
          <option value="Notebooks" {{ old('category') == 'Notebooks' ? 'selected' : '' }}>Notebooks</option>
          <option value="Pens & Pencils" {{ old('category') == 'Pens & Pencils' ? 'selected' : '' }}>Pens & Pencils</option>
          <option value="Art Supplies" {{ old('category') == 'Art Supplies' ? 'selected' : '' }}>Art Supplies</option>
          <option value="Bag" {{ old('category') == 'Bag' ? 'selected' : '' }}>Bag</option>
          <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
      </select>

      <input
        class="form-control mt-2"
        name="category_other"
        id="categoryOtherInput"
        placeholder="Enter category"
        style="display: none;"
        value="{{ old('category_other') }}"
      >
    </div>

    <div class="mb-3">
      <label class="form-label">Variant</label>
      <input class="form-control" name="variant" value="{{ old('variant') }}">
    </div>
    <div class="mb-3">
      <label class="form-label">Images</label>
      <input class="form-control @error('images.*') is-invalid @enderror" name="images[]" type="file" multiple accept="image/*">
      @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
      <small class="text-muted">You can upload multiple images</small>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Create</button>
      <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>

{{-- ⭐ JavaScript to show/hide "Other" input --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('categorySelect');
    const otherInput = document.getElementById('categoryOtherInput');

    function toggleOtherInput() {
        if (select.value === 'Other') {
            otherInput.style.display = 'block';
        } else {
            otherInput.style.display = 'none';
            otherInput.value = '';
        }
    }

    toggleOtherInput();
    select.addEventListener('change', toggleOtherInput);
});
</script>

@endsection
