document.addEventListener('DOMContentLoaded', function(){
  // Map modal - pick address button
  const pickBtn = document.getElementById('pickAddressBtn');
  if(pickBtn){
    pickBtn.addEventListener('click', function(){
      // When user clicks "Use this address", populate the address field
      // This is a placeholder - integrate with keyless-google-maps-api here
      const addr = document.getElementById('shipping_address');
      const lat = document.getElementById('shipping_latitude');
      const lng = document.getElementById('shipping_longitude');
      
      // Example: if map coordinates are set, use them
      if(lat && lat.value && lng && lng.value) {
        // Address would be populated by the map picker
        // For now, just show a message
        if(addr && !addr.value) {
          addr.value = 'Address selected from map';
        }
      }
    });
  }

  // Auto-submit quantity changes in cart
  const quantityInputs = document.querySelectorAll('input[name="quantity"]');
  quantityInputs.forEach(input => {
    input.addEventListener('change', function() {
      if(this.form) {
        this.form.submit();
      }
    });
  });

  // Form validation
  const checkoutForm = document.getElementById('checkoutForm');
  if(checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
      const address = document.getElementById('shipping_address');
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
      
      if(!address || !address.value.trim()) {
        e.preventDefault();
        alert('Please enter a shipping address');
        address.focus();
        return false;
      }
      
      if(!paymentMethod) {
        e.preventDefault();
        alert('Please select a payment method');
        return false;
      }
    });
  }

  // Initialize Bootstrap tooltips if any
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

