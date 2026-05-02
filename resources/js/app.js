import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();




// Custom JavaScript for order management
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.fixed.top-20.right-4');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });

    // Quantity increment/decrement functionality
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        const decrementBtn = input.parentElement.querySelector('.decrement-btn');
        const incrementBtn = input.parentElement.querySelector('.increment-btn');

        if (decrementBtn) {
            decrementBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }

        if (incrementBtn) {
            incrementBtn.addEventListener('click', () => {
                let value = parseInt(input.value);
                input.value = value + 1;
                input.dispatchEvent(new Event('change'));
            });
        }
    });
});
