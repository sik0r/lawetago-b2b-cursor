import { Controller } from '@hotwired/stimulus';

/**
 * Company controller for handling company-related actions
 */
export default class extends Controller {
    static targets = ['activateButton', 'statusBadge'];
    static values = {
        id: Number,
        activateUrl: String
    };

    connect() {
        console.log('Company controller connected');
        // Initialize toast library if needed
        if (window.Toastify) {
            this.toastify = window.Toastify;
        }
    }

    /**
     * Handle company activation
     * @param {Event} event - The click event
     */
    async activate(event) {
        event.preventDefault();
        
        const button = this.activateButtonTarget;
        const originalText = button.innerHTML;
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Aktywowanie...';
        
        try {
            // Send activation request
            const response = await fetch(this.activateUrlValue, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Wystąpił błąd podczas aktywacji firmy.');
            }
            
            // Show success message
            this.showToast('success', data.message || 'Firma została pomyślnie aktywowana.');
            
            // Update UI to reflect the new status
            if (this.hasStatusBadgeTarget) {
                this.statusBadgeTarget.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                this.statusBadgeTarget.textContent = 'aktywna';
            }
            
            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } catch (error) {
            // Show error message
            this.showToast('error', error.message);
            
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
        }
    }
    
    /**
     * Display a toast notification
     * @param {string} type - The type of toast (success, error)
     * @param {string} message - The message to display
     */
    showToast(type, message) {
        if (this.toastify) {
            // Use Toastify library if available
            this.toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: type === 'success' ? '#10B981' : '#EF4444',
            }).showToast();
        } else {
            // Fallback to alert
            alert(message);
        }
    }
} 