// Main JavaScript for Kopi Cepoko Website

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initMobileMenu();
    initSmoothScrolling();
    initImageLazyLoading();
    initContactForm();
    initProductOrder();
    initGalleryModal();
    initBackToTop();
});

// Mobile Menu Toggle
function initMobileMenu() {
    const toggleBtn = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            toggleBtn.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!toggleBtn.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                toggleBtn.classList.remove('active');
            }
        });
        
        // Close menu when clicking on menu links
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                toggleBtn.classList.remove('active');
            });
        });
    }
}

// Smooth Scrolling for Anchor Links
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Lazy Loading for Images
function initImageLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        images.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
    }
}

// Contact Form Handling
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Mengirim...';
            
            // Submit form
            fetch('backend/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Pesan berhasil dikirim! Kami akan menghubungi Anda segera.', 'success');
                    contactForm.reset();
                } else {
                    showAlert(data.message || 'Terjadi kesalahan. Silakan coba lagi.', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    }
}

// Product Order Functionality
function initProductOrder() {
    const orderBtns = document.querySelectorAll('.btn-order');
    
    orderBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            
            openOrderModal(productId, productName, productPrice);
        });
    });
}

// Open Order Modal
function openOrderModal(productId, productName, productPrice) {
    const modal = createOrderModal(productId, productName, productPrice);
    document.body.appendChild(modal);
    modal.style.display = 'flex';
    
    // Close modal events
    const closeBtn = modal.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
        document.body.removeChild(modal);
    });
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
    
    // Handle order form
    const orderForm = modal.querySelector('#orderForm');
    orderForm.addEventListener('submit', handleOrderSubmit);
}

// Create Order Modal HTML
function createOrderModal(productId, productName, productPrice) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h4>Pesan ${productName}</h4>
                <button type="button" class="modal-close">&times;</button>
            </div>
            <form id="orderForm">
                <input type="hidden" name="product_id" value="${productId}">
                <input type="hidden" name="product_name" value="${productName}">
                <input type="hidden" name="product_price" value="${productPrice}">
                
                <div class="form-group">
                    <label for="quantity">Jumlah:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_name">Nama Lengkap:</label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">Nomor WhatsApp:</label>
                    <input type="tel" id="customer_phone" name="customer_phone" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_email">Email (Opsional):</label>
                    <input type="email" id="customer_email" name="customer_email" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="customer_address">Alamat Pengiriman:</label>
                    <textarea id="customer_address" name="customer_address" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="notes">Catatan (Opsional):</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="order-summary">
                    <h5>Ringkasan Pesanan:</h5>
                    <p>Produk: ${productName}</p>
                    <p>Harga: <span id="unit-price">${formatCurrency(productPrice)}</span></p>
                    <p>Jumlah: <span id="order-quantity">1</span></p>
                    <p><strong>Total: <span id="total-price">${formatCurrency(productPrice)}</span></strong></p>
                </div>
                
                <div class="modal-actions">
                    <button type="submit" class="btn btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
                    </button>
                </div>
            </form>
        </div>
    `;
    
    // Update total when quantity changes
    const quantityInput = modal.querySelector('#quantity');
    quantityInput.addEventListener('change', function() {
        const quantity = parseInt(this.value) || 1;
        const price = parseFloat(productPrice);
        const total = quantity * price;
        
        modal.querySelector('#order-quantity').textContent = quantity;
        modal.querySelector('#total-price').textContent = formatCurrency(total);
    });
    
    return modal;
}

// Handle Order Form Submit
function handleOrderSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const orderData = {
        product_id: formData.get('product_id'),
        product_name: formData.get('product_name'),
        product_price: parseFloat(formData.get('product_price')),
        quantity: parseInt(formData.get('quantity')),
        customer_name: formData.get('customer_name'),
        customer_phone: formData.get('customer_phone'),
        customer_email: formData.get('customer_email'),
        customer_address: formData.get('customer_address'),
        notes: formData.get('notes')
    };
    
    // Calculate total
    const total = orderData.quantity * orderData.product_price;
    
    // Generate WhatsApp message
    const message = generateWhatsAppMessage(orderData, total);
    
    // Open WhatsApp
    const whatsappUrl = `https://wa.me/628123456789?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
    
    // Close modal
    const modal = e.target.closest('.modal');
    document.body.removeChild(modal);
    
    showAlert('Pesanan akan diteruskan ke WhatsApp. Silakan kirim pesan tersebut!', 'success');
}

// Generate WhatsApp Message
function generateWhatsAppMessage(orderData, total) {
    let message = "🌟 *PESANAN KOPI CEPOKO* 🌟\n\n";
    message += "📋 *Detail Pesanan:*\n";
    message += `Nama: ${orderData.customer_name}\n`;
    message += `Telepon: ${orderData.customer_phone}\n`;
    
    if (orderData.customer_email) {
        message += `Email: ${orderData.customer_email}\n`;
    }
    
    message += `Alamat: ${orderData.customer_address}\n\n`;
    
    message += "🛒 *Produk yang Dipesan:*\n";
    message += `• ${orderData.product_name}\n`;
    message += `  Qty: ${orderData.quantity} x ${formatCurrency(orderData.product_price)} = ${formatCurrency(total)}\n\n`;
    
    message += `💰 *Total: ${formatCurrency(total)}*\n`;
    
    if (orderData.notes) {
        message += `\n📝 *Catatan:*\n${orderData.notes}\n`;
    }
    
    message += "\n🙏 Terima kasih telah memesan Kopi Cepoko!";
    
    return message;
}

// Gallery Modal
function initGalleryModal() {
    const galleryItems = document.querySelectorAll('.gallery-item img');
    
    galleryItems.forEach(img => {
        img.addEventListener('click', function() {
            openGalleryModal(this.src, this.alt);
        });
    });
}

// Open Gallery Modal
function openGalleryModal(imageSrc, imageAlt) {
    const modal = document.createElement('div');
    modal.className = 'modal gallery-modal';
    modal.innerHTML = `
        <div class="modal-content gallery-modal-content">
            <button type="button" class="modal-close">&times;</button>
            <img src="${imageSrc}" alt="${imageAlt}" class="gallery-modal-img">
            <p class="gallery-modal-caption">${imageAlt}</p>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.style.display = 'flex';
    
    // Close events
    const closeBtn = modal.querySelector('.modal-close');
    closeBtn.addEventListener('click', () => {
        document.body.removeChild(modal);
    });
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function escapeHandler(e) {
        if (e.key === 'Escape') {
            document.body.removeChild(modal);
            document.removeEventListener('keydown', escapeHandler);
        }
    });
}

// Back to Top Button
function initBackToTop() {
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: none;
        cursor: pointer;
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    document.body.appendChild(backToTopBtn);
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'flex';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Scroll to top when clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Utility Functions

// Show Alert
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 300px;
        z-index: 2000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(alert);
            }, 300);
        }
    }, 5000);
    
    // Click to dismiss
    alert.addEventListener('click', () => {
        alert.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (alert.parentNode) {
                document.body.removeChild(alert);
            }
        }, 300);
    });
}

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

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

// Search Functionality
function initSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const debouncedSearch = debounce(performSearch, 300);
        searchInput.addEventListener('input', debouncedSearch);
    }
}

function performSearch(e) {
    const query = e.target.value.trim();
    if (query.length < 2) return;
    
    fetch(`backend/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

function displaySearchResults(results) {
    const resultsContainer = document.getElementById('searchResults');
    if (!resultsContainer) return;
    
    if (results.length === 0) {
        resultsContainer.innerHTML = '<p class="text-center">Tidak ada produk yang ditemukan.</p>';
        return;
    }
    
    const html = results.map(product => `
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="backend/uploads/products/${product.image}" class="card-img" alt="${product.name}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.short_description}</p>
                    <div class="card-price">${formatCurrency(product.price)}</div>
                    <button class="btn btn-primary btn-order" 
                            data-product-id="${product.id}"
                            data-product-name="${product.name}"
                            data-product-price="${product.price}">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    resultsContainer.innerHTML = html;
    
    // Re-initialize order buttons
    initProductOrder();
}

// Animation on Scroll
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    
    if ('IntersectionObserver' in window) {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, {
            threshold: 0.1
        });
        
        animatedElements.forEach(el => animationObserver.observe(el));
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes fadeInUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .animate-on-scroll.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .gallery-modal-content {
        max-width: 90vw;
        max-height: 90vh;
        text-align: center;
        padding: 20px;
    }
    
    .gallery-modal-img {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
    }
    
    .gallery-modal-caption {
        margin-top: 15px;
        font-style: italic;
        color: #666;
    }
    
    .back-to-top:hover {
        background: var(--secondary-color) !important;
        transform: translateY(-2px);
    }
    
    .order-summary {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
    }
    
    .modal-actions {
        text-align: center;
        margin-top: 20px;
    }
`;
document.head.appendChild(style);