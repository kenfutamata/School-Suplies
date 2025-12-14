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
		<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
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
	@include('partials.footer')
	<!-- Bootstrap JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="/js/site.js"></script>

	<script>
	// Global confirmation handler: intercept forms with a DELETE _method or elements with data-confirm
	document.addEventListener('DOMContentLoaded', function(){
	    // Initialize Bootstrap toasts
	    const toastElement = document.getElementById('flashToast');
	    if (toastElement) {
	        const toast = new bootstrap.Toast(toastElement);
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
	});
	</script>
</body>
</html>
