        </div> <!-- End content-wrapper -->
    </main> <!-- End main-content -->

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-text">
                <span>&copy; <?php echo date('Y'); ?> Travel Bus Indonesia. All rights reserved.</span>
            </div>
            <div class="footer-links">
                <small class="text-muted">
                    Version 1.0 | 
                    <a href="#" class="text-decoration-none">Bantuan</a> | 
                    <a href="#" class="text-decoration-none">Kontak</a>
                </small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional JS -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Custom Scripts -->
    <script>
        // Global JavaScript functions
        
        // Confirm delete action
        function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
            return confirm(message);
        }
        
        // Show loading state
        function showLoading(element) {
            if (element) {
                element.disabled = true;
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                element.dataset.originalText = originalText;
            }
        }
        
        // Hide loading state
        function hideLoading(element) {
            if (element && element.dataset.originalText) {
                element.disabled = false;
                element.innerHTML = element.dataset.originalText;
                delete element.dataset.originalText;
            }
        }
        
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
        
        // Format date
        function formatDate(date) {
            return new Intl.DateTimeFormat('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(new Date(date));
        }
        
        // Debounce function for search
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
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Initialize popovers
        document.addEventListener('DOMContentLoaded', function() {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
        
        // Form validation helper
        function validateForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return false;
            
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            return isValid;
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-persistent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
        
        // Add fade-in animation to content
        document.addEventListener('DOMContentLoaded', function() {
            const contentWrapper = document.querySelector('.content-wrapper');
            if (contentWrapper) {
                contentWrapper.classList.add('fade-in');
            }
        });
    </script>
    
    <style>
        /* Footer styles menggunakan unified theme variables */
        .main-footer {
            background: var(--bg-main);
            border-top: 1px solid var(--border-light);
            padding: var(--spacing-lg) 0;
            margin-top: var(--spacing-2xl);
            margin-left: var(--sidebar-width);
            transition: all var(--transition-normal);
        }
        
        .main-content.expanded + .main-footer,
        .main-footer.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 var(--spacing-xl);
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .footer-links a {
            color: var(--text-secondary);
            transition: color var(--transition-fast);
        }
        
        .footer-links a:hover {
            color: var(--primary-blue);
        }
        
        @media (max-width: 768px) {
            .main-footer {
                margin-left: 0;
            }
            
            .footer-content {
                flex-direction: column;
                gap: var(--spacing-md);
                text-align: center;
                padding: 0 var(--spacing-lg);
            }
        }
    </style>
</body>
</html>
