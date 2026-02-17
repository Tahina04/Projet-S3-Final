/**
 * BNGRC - Application JavaScript
 * Gestion des dons et des sinistrés
 */

// Utility functions
const Utils = {
    /**
     * Format number with thousand separators
     */
    numberFormat: function(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    },
    
    /**
     * Show alert message
     */
    showAlert: function(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('main');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    },
    
    /**
     * Confirm delete action
     */
    confirmDelete: function(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
        return confirm(message);
    }
};

// Form validation
const FormValidator = {
    /**
     * Validate quantity input
     */
    validateQuantity: function(input, maxValue) {
        const value = parseFloat(input.value);
        const feedback = input.nextElementSibling;
        
        if (isNaN(value) || value <= 0) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        }
        
        if (value > maxValue) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        }
        
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    },
    
    /**
     * Validate required field
     */
    validateRequired: function(input) {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        }
        
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
};

// Table utilities
const TableUtils = {
    /**
     * Sort table by column
     */
    sortTable: function(tableId, columnIndex) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            // Try numeric comparison
            const aNum = parseFloat(aText.replace(/[^0-9.-]/g, ''));
            const bNum = parseFloat(bText.replace(/[^0-9.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return aNum - bNum;
            }
            
            return aText.localeCompare(bText);
        });
        
        rows.forEach(row => tbody.appendChild(row));
    },
    
    /**
     * Filter table rows
     */
    filterTable: function(tableId, searchTerm) {
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm.toLowerCase())) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Add validation to forms
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Export utilities for use in views
window.Utils = Utils;
window.FormValidator = FormValidator;
window.TableUtils = TableUtils;
