<x-app-layout>
    <x-slot name="content">
        <div class="container">
            <h1 class="mb-4">Complete Payment</h1>
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>Order #{{ $order->order_number }}</h5>
                            <p>Total: ₱{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.checkout.process-payment', $order) }}" method="POST" data-confirm="Are you sure you want to complete this payment? Amount: ₱{{ number_format($order->total_amount, 2) }}. This action cannot be undone.">
                                @csrf
                                @if($order->payment->method === 'gcash')
                                    <div class="mb-3">
                                        <label for="gcash_number" class="form-label">GCash Number</label>
                                        <input type="text" class="form-control" id="gcash_number" name="gcash_number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gcash_name" class="form-label">Account Name</label>
                                        <input type="text" class="form-control" id="gcash_name" name="gcash_name" required>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="card_name" class="form-label">Cardholder Name</label>
                                        <input type="text" class="form-control" id="card_name" name="card_name" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="card_expiry" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="card_cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="card_cvv" name="card_cvv" required>
                                        </div>
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-primary w-100">Complete Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>









