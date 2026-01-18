// Wishlist functionality - Xử lý thêm/xóa sản phẩm khỏi wishlist
document.addEventListener('DOMContentLoaded', function() {
    console.log('Wishlist script loaded successfully');
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Tạo toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: 350px;
            max-width: 500px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            backdrop-filter: blur(10px);
            animation: slideInRight 0.3s ease-out;
        `;
        
        // Icon based on type
        const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        toast.innerHTML = `<i class="${icon} me-2"></i>${message}`;
        
        // Thêm animation CSS nếu chưa có
        if (!document.querySelector('#toast-animations')) {
            const style = document.createElement('style');
            style.id = 'toast-animations';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(toast);
        
        // Auto remove after 4 seconds với animation
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 4000);
    }
    
    // Update wishlist counter in navigation
    function updateWishlistCounter() {
        const wishlistLink = document.querySelector('a[href*="wishlist"]');
        if (wishlistLink) {
            // Lấy số lượng từ session (có thể cần AJAX call)
            fetch('/wishlist/count')
                .then(response => response.json())
                .then(data => {
                    const badge = wishlistLink.querySelector('.badge');
                    if (data.count > 0) {
                        if (!badge) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            newBadge.innerHTML = `${data.count}<span class="visually-hidden">sản phẩm yêu thích</span>`;
                            wishlistLink.appendChild(newBadge);
                        } else {
                            badge.textContent = data.count;
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                })
                .catch(error => console.log('Could not update wishlist counter'));
        }
    }
    
    // Wishlist toggle functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-toggle');
    console.log(`Found ${wishlistButtons.length} wishlist buttons`);
    
    wishlistButtons.forEach((btn, index) => {
        console.log(`Button ${index}:`, btn);
        
        // Đảm bảo nút có thể click được
        btn.style.pointerEvents = 'auto';
        btn.style.cursor = 'pointer';
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-id');
            const isActive = this.classList.contains('active');
            
            console.log(`Product ${productId} - Current state: ${isActive ? 'in wishlist' : 'not in wishlist'}`);
            
            // Xác định action và URL
            let action, url, method;
            if (isActive) {
                // Nếu đã có trong wishlist -> xóa
                action = 'remove';
                url = `/wishlist/remove/${productId}`;
                method = 'DELETE';
            } else {
                // Nếu chưa có trong wishlist -> thêm
                action = 'add';
                url = `/wishlist/add/${productId}`;
                method = 'POST';
            }
            
            console.log(`Action: ${action}, URL: ${url}, Method: ${method}`);
            
            // Disable button during request
            this.disabled = true;
            const originalContent = this.innerHTML;
            
            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response text:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Parsed data:', data);
                
                if (data.success) {
                    // Toggle active state dựa trên action
                    if (action === 'add') {
                        this.classList.add('active');
                        showToast(data.message || 'Đã thêm vào danh sách yêu thích!', 'success');
                    } else {
                        this.classList.remove('active');
                        showToast(data.message || 'Đã xóa khỏi danh sách yêu thích!', 'success');
                    }
                    
                    // Update counter
                    updateWishlistCounter();
                } else {
                    showToast(data.message || 'Có lỗi xảy ra!', 'danger');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
                showToast('Có lỗi xảy ra. Vui lòng thử lại!', 'danger');
            })
            .finally(() => {
                // Restore button state
                this.disabled = false;
                this.innerHTML = originalContent;
            });
        });
    });
});
