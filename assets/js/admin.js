// Admin Dashboard JavaScript for Kopi Cepoko

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initDataTables();
    initForms();
    initModals();
    initFileUploads();
    initCharts();
    initNotifications();
});

// Sidebar Management
function initSidebar() {
    const toggleBtn = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.admin-sidebar');
    const mainContent = document.querySelector('.admin-main');
    
    if (toggleBtn && sidebar && mainContent) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Restore state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
    }
    
    // Mobile sidebar toggle
    const mobileSidebarToggle = document.querySelector('.mobile-sidebar-toggle');
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !mobileSidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
}

// Data Tables Initialization
function initDataTables() {
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const column = this.dataset.sort;
                const currentOrder = this.dataset.order || 'asc';
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                
                sortTable(table, column, newOrder);
                
                // Update header
                headers.forEach(h => h.removeAttribute('data-order'));
                this.dataset.order = newOrder;
                
                // Update visual indicator
                headers.forEach(h => {
                    h.innerHTML = h.innerHTML.replace(/ ↑| ↓/g, '');
                });
                this.innerHTML += newOrder === 'asc' ? ' ↑' : ' ↓';
            });
        });
        
        // Add search functionality
        const searchInput = document.querySelector(`[data-table="${table.id}"]`);
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                filterTable(table, this.value);
            }, 300));
        }
    });
}

// Table Sorting
function sortTable(table, column, order) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.querySelector(`[data-value="${column}"]`)?.textContent || 
                      a.cells[getColumnIndex(table, column)]?.textContent || '';
        const bValue = b.querySelector(`[data-value="${column}"]`)?.textContent || 
                      b.cells[getColumnIndex(table, column)]?.textContent || '';
        
        const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
        const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return order === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        return order === 'asc' ? 
            aValue.localeCompare(bValue) : 
            bValue.localeCompare(aValue);
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// Get Column Index
function getColumnIndex(table, column) {
    const headers = table.querySelectorAll('th');
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].dataset.sort === column) {
            return i;
        }
    }
    return 0;
}

// Table Filtering
function filterTable(table, searchTerm) {
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matches = text.includes(searchTerm.toLowerCase());
        row.style.display = matches ? '' : 'none';
    });
}

// Form Handling
function initForms() {
    const forms = document.querySelectorAll('.admin-form form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return false;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Menyimpan...';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }, 5000);
            }
        });
        
        // Auto-save for content forms
        if (form.classList.contains('auto-save')) {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('change', debounce(function() {
                    autoSaveForm(form);
                }, 2000));
            });
        }
    });
}

// Form Validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    // Email validation
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            showFieldError(field, 'Format email tidak valid');
            isValid = false;
        }
    });
    
    // Number validation
    const numberFields = form.querySelectorAll('input[type="number"]');
    numberFields.forEach(field => {
        if (field.value && isNaN(field.value)) {
            showFieldError(field, 'Harus berupa angka');
            isValid = false;
        }
    });
    
    return isValid;
}

// Show Field Error
function showFieldError(field, message) {
    clearFieldError(field);
    
    const error = document.createElement('div');
    error.className = 'field-error';
    error.textContent = message;
    error.style.cssText = 'color: #e74c3c; font-size: 0.875rem; margin-top: 5px;';
    
    field.parentNode.appendChild(error);
    field.style.borderColor = '#e74c3c';
}

// Clear Field Error
function clearFieldError(field) {
    const error = field.parentNode.querySelector('.field-error');
    if (error) {
        error.remove();
    }
    field.style.borderColor = '';
}

// Email Validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Auto Save Form
function autoSaveForm(form) {
    const formData = new FormData(form);
    formData.append('auto_save', '1');
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Perubahan telah disimpan otomatis', 'success', 2000);
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

// Modal Management
function initModals() {
    // Open modal buttons
    const modalTriggers = document.querySelectorAll('[data-modal]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    // Close modal buttons
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });
    
    // Close modal on backdrop click
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

// Open Modal
function openModal(modal) {
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Focus first input
    const firstInput = modal.querySelector('input, textarea, select');
    if (firstInput) {
        setTimeout(() => firstInput.focus(), 100);
    }
}

// Close Modal
function closeModal(modal) {
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    // Reset form if exists
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
        clearFormErrors(form);
    }
}

// Clear Form Errors
function clearFormErrors(form) {
    const errors = form.querySelectorAll('.field-error');
    errors.forEach(error => error.remove());
    
    const fields = form.querySelectorAll('input, textarea, select');
    fields.forEach(field => {
        field.style.borderColor = '';
    });
}

// File Upload Handling
function initFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleFileUpload(this);
        });
        
        // Drag and drop
        const dropZone = input.parentNode.querySelector('.file-drop-zone');
        if (dropZone) {
            initDragAndDrop(dropZone, input);
        }
    });
}

// Handle File Upload
function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (file.size > maxSize) {
        showNotification('Ukuran file terlalu besar (maksimal 5MB)', 'danger');
        input.value = '';
        return;
    }
    
    if (!allowedTypes.includes(file.type)) {
        showNotification('Tipe file tidak didukung (gunakan JPG, PNG, GIF, atau WebP)', 'danger');
        input.value = '';
        return;
    }
    
    // Show preview
    showImagePreview(input, file);
}

// Show Image Preview
function showImagePreview(input, file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = input.parentNode.querySelector('.upload-preview') || 
                       createPreviewElement(input);
        
        preview.innerHTML = `
            <img src="${e.target.result}" alt="Preview">
            <button type="button" class="btn-remove-image" onclick="removeImagePreview(this)">
                <i class="fas fa-times"></i> Hapus
            </button>
        `;
    };
    reader.readAsDataURL(file);
}

// Create Preview Element
function createPreviewElement(input) {
    const preview = document.createElement('div');
    preview.className = 'upload-preview';
    input.parentNode.appendChild(preview);
    return preview;
}

// Remove Image Preview
function removeImagePreview(button) {
    const preview = button.parentNode;
    const input = preview.parentNode.querySelector('input[type="file"]');
    
    preview.innerHTML = '';
    input.value = '';
}

// Drag and Drop
function initDragAndDrop(dropZone, input) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            handleFileUpload(input);
        }
    });
    
    dropZone.addEventListener('click', function() {
        input.click();
    });
}

// Charts Initialization
function initCharts() {
    // Dashboard statistics chart
    const chartCanvas = document.getElementById('statsChart');
    if (chartCanvas && window.Chart) {
        const ctx = chartCanvas.getContext('2d');
        
        // Sample data - replace with real data from server
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Penjualan',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// Notifications
function initNotifications() {
    // Check for new notifications every 30 seconds
    setInterval(checkNotifications, 30000);
    
    // Mark notification as read
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            markNotificationAsRead(this.dataset.id);
        });
    });
}

// Check Notifications
function checkNotifications() {
    fetch('backend/notifications.php')
        .then(response => response.json())
        .then(data => {
            updateNotificationCount(data.unread_count);
            updateNotificationList(data.notifications);
        })
        .catch(error => {
            console.error('Notification check error:', error);
        });
}

// Update Notification Count
function updateNotificationCount(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Update Notification List
function updateNotificationList(notifications) {
    const list = document.querySelector('.notification-list');
    if (list) {
        list.innerHTML = notifications.map(notification => `
            <div class="notification-item ${notification.is_read ? '' : 'unread'}" data-id="${notification.id}">
                <div class="notification-content">
                    <h6>${notification.title}</h6>
                    <p>${notification.message}</p>
                    <small>${formatDate(notification.created_at)}</small>
                </div>
            </div>
        `).join('');
    }
}

// Mark Notification as Read
function markNotificationAsRead(id) {
    fetch('backend/mark_notification_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            checkNotifications();
        }
    });
}

// Show Notification
function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `alert-admin alert-${type}-admin`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 300px;
        z-index: 2000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, duration);
    
    // Click to dismiss
    notification.addEventListener('click', () => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    });
}

// Bulk Actions
function initBulkActions() {
    const bulkForm = document.getElementById('bulkActionForm');
    if (bulkForm) {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkActionSelect = document.getElementById('bulkAction');
        const bulkActionBtn = document.getElementById('bulkActionBtn');
        
        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });
        }
        
        // Individual checkbox change
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButton);
        });
        
        // Bulk action button
        if (bulkActionBtn) {
            bulkActionBtn.addEventListener('click', function() {
                const selectedItems = Array.from(itemCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                const action = bulkActionSelect.value;
                
                if (selectedItems.length === 0) {
                    showNotification('Pilih minimal satu item', 'warning');
                    return;
                }
                
                if (!action) {
                    showNotification('Pilih aksi yang akan dilakukan', 'warning');
                    return;
                }
                
                performBulkAction(action, selectedItems);
            });
        }
    }
}

// Update Bulk Action Button
function updateBulkActionButton() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const bulkActionBtn = document.getElementById('bulkActionBtn');
    
    if (bulkActionBtn) {
        bulkActionBtn.disabled = checkedBoxes.length === 0;
        bulkActionBtn.textContent = `Terapkan (${checkedBoxes.length} dipilih)`;
    }
}

// Perform Bulk Action
function performBulkAction(action, selectedItems) {
    if (action === 'delete') {
        if (!confirm('Apakah Anda yakin ingin menghapus item yang dipilih?')) {
            return;
        }
    }
    
    const formData = new FormData();
    formData.append('action', action);
    formData.append('items', JSON.stringify(selectedItems));
    
    fetch('backend/bulk_actions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Bulk action error:', error);
        showNotification('Terjadi kesalahan', 'danger');
    });
}

// Utility Functions

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Format Date
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

// Confirm Delete
function confirmDelete(url, message = 'Apakah Anda yakin ingin menghapus item ini?') {
    if (confirm(message)) {
        window.location.href = url;
    }
}

// AJAX Form Submit
function ajaxSubmit(form, callback) {
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (callback) {
            callback(data);
        }
    })
    .catch(error => {
        console.error('AJAX submit error:', error);
        showNotification('Terjadi kesalahan', 'danger');
    });
}

// Export Functions
function exportData(format, table = 'current') {
    const url = `backend/export.php?format=${format}&table=${table}`;
    window.open(url, '_blank');
}

// Print Table
function printTable(tableId) {
    const table = document.getElementById(tableId);
    if (table) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .btn, .actions { display: none; }
                    </style>
                </head>
                <body>
                    ${table.outerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initBulkActions();
});

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
    if (e.error.message.includes('fetch')) {
        showNotification('Koneksi bermasalah. Silakan periksa koneksi internet Anda.', 'warning');
    }
});

// Add CSS animations for admin
const adminStyle = document.createElement('style');
adminStyle.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .notification-item.unread {
        background-color: #f8f9fa;
        border-left: 3px solid #3498db;
    }
    
    .btn-remove-image {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.75rem;
        margin-top: 10px;
        cursor: pointer;
    }
    
    .btn-remove-image:hover {
        background: #c0392b;
    }
    
    .upload-preview img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
`;
document.head.appendChild(adminStyle);