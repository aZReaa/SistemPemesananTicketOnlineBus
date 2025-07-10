<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Tiket Bus</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        
        .brand-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: white;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 550px;
        }
        
        .brand-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: rgba(255,255,255,0.2);
            padding: 1.5rem;
            border-radius: 50%;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }
        
        .feature-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .feature-list i {
            margin-right: 1rem;
            font-size: 1.3rem;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-section {
            padding: 3rem 2.5rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            height: 60px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
        
        .register-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link:hover {
            color: #0056b3;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .brand-section {
                min-height: auto;
                padding: 2rem 1.5rem;
            }
            
            .form-section {
                padding: 2rem 1.5rem;
            }
            
            .brand-icon {
                font-size: 3rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="auth-card">
                        <div class="row g-0">
                            <!-- Brand Section -->
                            <div class="col-lg-5">
                                <div class="brand-section">
                                    <div class="brand-icon">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <h2 class="mb-3">Selamat Datang Kembali</h2>
                                    <p class="mb-0">Masuk ke akun Anda untuk melanjutkan pemesanan tiket bus</p>
                                    
                                    <ul class="feature-list">
                                        <li>
                                            <i class="fas fa-shield-alt"></i>
                                            <span>Keamanan Terjamin</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-clock"></i>
                                            <span>Akses 24/7</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-heart"></i>
                                            <span>Mudah & Nyaman</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Form Section -->
                            <div class="col-lg-7">
                                <div class="form-section">
                                    <h1 class="page-title">Masuk ke Akun</h1>
                                    <p class="page-subtitle">Silakan masuk untuk melanjutkan ke dashboard Anda</p>
                                    
                                    <!-- Alert Messages -->
                                    <?php if (isset($_SESSION['error_message'])): ?>
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($_SESSION['success_message'])): ?>
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <form action="index.php?page=process_login" method="POST" id="loginForm">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   placeholder="Username atau Email" required>
                                            <label for="username">
                                                <i class="fas fa-user me-2"></i>Username atau Email
                                            </label>
                                        </div>
                                        
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   placeholder="Password" required>
                                            <label for="password">
                                                <i class="fas fa-lock me-2"></i>Password
                                            </label>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                                <label class="form-check-label text-muted" for="remember">
                                                    Ingat saya
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-login">
                                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                        </button>
                                    </form>
                                    
                                    <div class="divider">
                                        <span>atau</span>
                                    </div>
                                    
                                    <div class="text-center">
                                        <p class="mb-0">
                                            Belum punya akun? 
                                            <a href="index.php?page=register" class="register-link">Daftar sekarang</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form Enhancement JS -->
    <script>
        // Add loading animation on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds (fallback)
            setTimeout(function() {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Add focus effects
        document.querySelectorAll('.form-control').forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
