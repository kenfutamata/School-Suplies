<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-uppercase text-secondary small mb-1">Customer Area</p>
            <h1 class="fw-bold mb-0">Shopping Cart</h1>
        </div>
        <span class="badge text-bg-dark text-uppercase">{{ $cartItems->count() }} Items</span>
    </x-slot>

    <div class="py-4">
        @if ($cartItems->count() > 0)
            <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <input type="checkbox" id="selectAll" class="form-check-input" checked>
                                <label for="selectAll" class="form-check-label ms-2 fw-semibold">Select All</label>
                            </div>
                        </div>
                        @foreach ($cartItems as $item)
                            <div class="card border-0 shadow-sm mb-3 cart-item-card" data-item-id="{{ $item->id }}" data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}">
                                <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-md-center">
                                    <div class="form-check flex-shrink-0">
                                        <input class="form-check-input cart-item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="item_{{ $item->id }}" checked onchange="updateOrderSummary()">
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if ($item->product->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                                class="rounded" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="bg-secondary rounded" style="width: 120px; height: 120px;"></div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between flex-wrap gap-2">
                                            <div>
                                                <h5 class="mb-1">{{ $item->product->name }}</h5>
                                                <p class="text-muted mb-2">{{ $item->product->seller->business_name ?? 'School Supply Seller' }}</p>
                                                <span class="h5 text-primary mb-0 d-block">₱{{ number_format($item->product->price, 2) }}</span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted text-uppercase d-block">Subtotal</small>
                                                <span class="h5 mb-0 item-subtotal" data-item-id="{{ $item->id }}">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <label class="text-uppercase small text-muted mb-0">Qty</label>
                                                <input type="number"
                                                    class="form-control quantity-input"
                                                    style="width: 90px;"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    max="{{ $item->product->stock }}"
                                                    data-item-id="{{ $item->id }}"
                                                    data-update-url="{{ route('customer.cart.update', $item) }}"
                                                    onchange="updateQuantity(this)">
                                            </div>
                                            <form action="{{ route('customer.cart.destroy', $item) }}" method="POST" class="ms-auto" data-confirm="Are you sure you want to remove this item from your cart?" onsubmit="return confirm('Are you sure you want to remove this item from your cart?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i> Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                            <div class="card-body">
                                <h5 class="mb-3">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Items</span>
                                    <span id="summary-item-count">{{ $cartItems->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal">₱{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total</strong>
                                    <strong id="summary-total">₱{{ number_format($total, 2) }}</strong>
                                </div>
                                <button type="button" class="btn btn-primary w-100 btn-lg mb-2" id="checkoutBtn">
                                    Proceed to Checkout
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center bg-white border rounded p-5 shadow-sm">
                <i class="bi bi-cart3 display-4 text-muted mb-3"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Discover notebooks, pens, art kits and more from our sellers.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary"><i class="bi bi-bag me-1"></i> Shop school supplies</a>
            </div>
        @endif
    </div>

    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateOrderSummary();
        });

        // Update order summary dynamically
        function updateOrderSummary() {
            const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
            let itemCount = 0;
            let subtotal = 0;

            checkedItems.forEach(checkbox => {
                const itemId = checkbox.value;
                const card = document.querySelector(`.cart-item-card[data-item-id="${itemId}"]`);
                if (card) {
                    const price = parseFloat(card.dataset.price);
                    const quantity = parseInt(card.dataset.quantity);
                    itemCount++;
                    subtotal += price * quantity;
                }
            });

            document.getElementById('summary-item-count').textContent = itemCount;
            document.getElementById('summary-subtotal').textContent = '₱' + subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            document.getElementById('summary-total').textContent = '₱' + subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            // Disable checkout button if no items selected
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (itemCount === 0) {
                checkoutBtn.disabled = true;
                checkoutBtn.textContent = 'Select items to checkout';
            } else {
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Proceed to Checkout';
            }
        }

        // Update quantity via AJAX
        function updateQuantity(input) {
            const itemId = input.dataset.itemId;
            const quantity = parseInt(input.value);
            const updateUrl = input.dataset.updateUrl;

            // Update the data attribute
            const card = document.querySelector(`.cart-item-card[data-item-id="${itemId}"]`);
            if (card) {
                card.dataset.quantity = quantity;
            }

            // Update subtotal for this item
            const price = parseFloat(card.dataset.price);
            const itemSubtotal = document.querySelector(`.item-subtotal[data-item-id="${itemId}"]`);
            if (itemSubtotal) {
                itemSubtotal.textContent = '₱' + (price * quantity).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Update order summary
            updateOrderSummary();

            // Send AJAX request to update quantity
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                             document.querySelector('input[name="_token"]')?.value ||
                             '{{ csrf_token() }}';

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    quantity: quantity,
                    _method: 'PUT'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    // Revert quantity
                    input.value = card.dataset.quantity;
                    updateOrderSummary();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update quantity. Please try again.');
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateOrderSummary();

            // Handle checkout button click - navigate directly to avoid form submission issues
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const checkedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                    if (checkedItems.length === 0) {
                        alert('Please select at least one item to checkout.');
                        return false;
                    }

                    // Build query string with selected items
                    const selectedIds = Array.from(checkedItems).map(cb => cb.value);
                    const queryString = selectedIds.map(id => `selected_items[]=${encodeURIComponent(id)}`).join('&');
                    const checkoutUrl = '{{ route("customer.checkout.index") }}?' + queryString;

                    // Navigate to checkout
                    window.location.href = checkoutUrl;
                });
            }
        });
    </script>
</x-app-layout>







