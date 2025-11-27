import './bootstrap';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Initialize Bootstrap tooltips
window.addEventListener('DOMContentLoaded', () => {
	const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	tooltipTriggerList.forEach((tooltipTriggerEl) => {
		new window.bootstrap.Tooltip(tooltipTriggerEl);
	});

	// Auto-show any toasts present
	const toastElList = [].slice.call(document.querySelectorAll('.toast'));
	toastElList.forEach((toastEl) => new window.bootstrap.Toast(toastEl).show());
});
