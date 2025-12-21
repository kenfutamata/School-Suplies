<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'School Supplies Store')</title>
	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/theme.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

	<style>
		/* Stock-related utility styles */
		.stock-badge {
			font-size: 0.75rem;
			padding: 0.25rem 0.5rem;
		}

		.pulse-animation {
			animation: pulse 2s infinite;
		}

		@keyframes pulse {
			0% {
				box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
			}
			70% {
				box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
			}
			100% {
				box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
			}
		}

		.toast-container {
			z-index: 1080;
		}

		/* Smooth transitions for stock updates */
		.stock-count {
			transition: all 0.3s ease;
		}

		.stock-count.updated {
			animation: highlight 0.6s ease;
		}

		@keyframes highlight {
			0%, 100% {
				background-color: transparent;
			}
			50% {
				background-color: rgba(255, 193, 7, 0.3);
			}
		}
	</style>

	@stack('styles')
</head>
<body>
	@include('partials.header')
	<main class="py-4">
		<div class="container">
            {{ $slot ?? '' }}
			@yield('content')
		</div>
	</main>

	{{-- Flash toasts (success / error) --}}
	@if(session('success') || session('error'))
		<div class="position-fixed bottom-0 end-0 p-3 toast-container">
			<div class="toast align-items-center text-bg-{{ session('success') ? 'success' : 'danger' }} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="flashToast" data-bs-autohide="true" data-bs-delay="5000">
				<div class="d-flex">
					<div class="toast-body">
						<i class="bi bi-{{ session('success') ? 'check-circle' : 'exclamation-circle' }} me-2"></i>
						{{ session('success') ?? session('error') }}
					</div>
					<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
	@endif

	{{-- Stock warning toast (for low stock items added to cart) --}}
	@if(session('low_stock_warning'))
		<div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 1081;">
			<div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true" id="stockWarningToast" data-bs-autohide="true" data-bs-delay="7000">
				<div class="d-flex">
					<div class="toast-body text-dark">
						<i class="bi bi-exclamation-triangle-fill me-2"></i>
						{{ session('low_stock_warning') }}
					</div>
					<button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			</div>
		</div>
	@endif

	@include('partials.footer')

	<!-- Bootstrap JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="/js/site.js"></script>

	<script>
	// Global confirmation handler and stock management utilities
	document.addEventListener('DOMContentLoaded', function(){
	    // Initialize Bootstrap toasts
	    const toastElement = document.getElementById('flashToast');
	    if (toastElement) {
	        const toast = new bootstrap.Toast(toastElement);
	        toast.show();
	    }

	    // Initialize stock warning toast
	    const stockWarningToast = document.getElementById('stockWarningToast');
	    if (stockWarningToast) {
	        const toast = new bootstrap.Toast(stockWarningToast);
	        toast.show();
	    }

	    // Intercept form submits - check for DELETE, PUT, PATCH methods or data-confirm attribute
	    document.addEventListener('submit', function(e){
	        const form = e.target;
	        if (!(form instanceof HTMLFormElement)) return;

	        // Skip GET forms - they don't need confirmation
	        if (form.method.toLowerCase() === 'get') return;

	        // Check for method override (DELETE, PUT, PATCH)
	        const methodInput = form.querySelector('input[name="_method"]');
	        const method = methodInput ? methodInput.value.toLowerCase() : form.method.toLowerCase();

	        // Check for data-confirm attribute
	        const confirmAttr = form.dataset.confirm;

	        // Show confirmation for DELETE, PUT, PATCH, or if data-confirm is present
	        if (method === 'delete' || method === 'put' || method === 'patch' || confirmAttr) {
	            const message = confirmAttr ||
	                (method === 'delete' ? 'Are you sure you want to delete this? This action cannot be undone.' :
	                 method === 'put' || method === 'patch' ? 'Are you sure you want to save these changes?' :
	                 'Are you sure you want to perform this action?');

	            if (!confirm(message)) {
	                e.preventDefault();
	                e.stopImmediatePropagation();
	                return false;
	            }
	        }
	    }, true);

	    // Buttons/links with data-confirm
	    document.addEventListener('click', function(e){
	        const btn = e.target.closest('[data-confirm]');
	        if (!btn) return;
	        const message = btn.dataset.confirm || 'Are you sure?';
	        if (!confirm(message)) {
	            e.preventDefault();
	            e.stopImmediatePropagation();
	            return false;
	        }
	    }, true);

	    // Stock utility functions
	    window.stockUtils = {
	        // Show a toast notification
	        showToast: function(message, type = 'info') {
	            const toastHTML = `
	                <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
	                    <div class="d-flex">
	                        <div class="toast-body">
	                            <i class="bi bi-${type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'x-circle' : 'info-circle'} me-2"></i>
	                            ${message}
	                        </div>
	                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
	                    </div>
	                </div>
	            `;

	            let container = document.querySelector('.toast-container');
	            if (!container) {
	                container = document.createElement('div');
	                container.className = 'position-fixed bottom-0 end-0 p-3 toast-container';
	                document.body.appendChild(container);
	            }

	            const tempDiv = document.createElement('div');
	            tempDiv.innerHTML = toastHTML;
	            const toastElement = tempDiv.firstElementChild;
	            container.appendChild(toastElement);

	            const toast = new bootstrap.Toast(toastElement);
	            toast.show();

	            // Remove toast element after it's hidden
	            toastElement.addEventListener('hidden.bs.toast', function() {
	                toastElement.remove();
	            });
	        },

	        // Check stock status for a product
	        checkStock: function(productId, callback) {
	            fetch(`/stock-status/${productId}`)
	                .then(response => response.json())
	                .then(data => {
	                    if (callback) callback(data);
	                })
	                .catch(error => {
	                    console.error('Error checking stock:', error);
	                });
	        },

	        // Update stock display on page
	        updateStockDisplay: function(productId, newStock) {
	            const stockElements = document.querySelectorAll(`[data-product-stock="${productId}"]`);
	            stockElements.forEach(element => {
	                element.textContent = newStock;
	                element.classList.add('updated');
	                setTimeout(() => element.classList.remove('updated'), 600);
	            });
	        }
	    };
	});
	</script>

	@stack('scripts')
</body>
</html>
