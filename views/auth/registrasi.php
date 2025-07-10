<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Tiket Bus</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .container-fluid {
            padding: 0;
            margin: 0;
            width: 100%;
        }
        
        .row {
            margin: 0;
            width: 100%;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            margin: auto;
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
            min-height: 600px;
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
        
        .btn-register {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
        
        .login-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link:hover {
            color: #0056b3;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
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
            font-size: 2rem;
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
        
        @media (max-width: 992px) {
            .auth-card {
                max-width: 700px;
            }
            
            .brand-section {
                min-height: auto;
                padding: 2.5rem 2rem;
            }
            
            .form-section {
                padding: 2.5rem 2rem;
            }
        }
        
        @media (max-width: 768px) {
            html, body {
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }
            
            .auth-container {
                height: auto;
                min-height: 100vh;
                padding: 0.5rem;
            }
            
            .auth-card {
                margin: 0.5rem auto;
            }
            
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
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .form-floating > .form-control {
                height: 55px;
            }
        }
        
        @media (max-width: 576px) {
            html, body {
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }
            
            .auth-container {
                height: auto;
                min-height: 100vh;
                padding: 0.25rem;
            }
            
            .auth-card {
                border-radius: 15px;
                margin: 0.25rem auto;
            }
            
            .brand-section {
                padding: 1.5rem 1rem;
            }
            
            .form-section {
                padding: 1.5rem 1rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0">
                <!-- Brand Section -->
                <div class="col-lg-5">
                    <div class="brand-section">
                                    <div class="brand-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <h2 class="mb-3">Bergabung dengan Kami</h2>
                                    <p class="mb-0">Daftar sekarang dan nikmati kemudahan pemesanan tiket bus</p>
                                    
                                    <ul class="feature-list">
                                        <li>
                                            <i class="fas fa-rocket"></i>
                                            <span>Pendaftaran Cepat</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>Mudah Digunakan</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-gift"></i>
                                            <span>Gratis Selamanya</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Form Section -->
                            <div class="col-lg-7">
                                <div class="form-section">
                                    <h1 class="page-title">Buat Akun Baru</h1>
                                    <p class="page-subtitle">Lengkapi formulir di bawah untuk mendaftar</p>
                                    
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
                                    
                                    <form action="index.php?page=register_process" method="POST" id="registerForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="username" name="username" 
                                                           placeholder="Username" required>
                                                    <label for="username">
                                                        <i class="fas fa-user me-2"></i>Username
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" id="email" name="email" 
                                                           placeholder="Email" required>
                                                    <label for="email">
                                                        <i class="fas fa-envelope me-2"></i>Email
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="password" name="password" 
                                                           placeholder="Password" required>
                                                    <label for="password">
                                                        <i class="fas fa-lock me-2"></i>Password
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="confirm_password" 
                                                           name="confirm_password" placeholder="Konfirmasi Password" required>
                                                    <label for="confirm_password">
                                                        <i class="fas fa-lock me-2"></i>Konfirmasi Password
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-floating">
                                            <input type="tel" class="form-control" id="no_telp" name="no_telp" 
                                                   placeholder="Nomor Telepon">
                                            <label for="no_telp">
                                                <i class="fas fa-phone me-2"></i>Nomor Telepon
                                            </label>
                                        </div>
                                        
                                        <div class="form-floating">
                                            <textarea class="form-control" id="alamat" name="alamat" 
                                                      placeholder="Alamat" style="height: 100px;"></textarea>
                                            <label for="alamat">
                                                <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                            </label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-register">
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        </button>
                                    </form>
                                    
                                    <div class="text-center mt-4">
                                        <p class="mb-0">
                                            Sudah punya akun? 
                                            <a href="index.php?page=login" class="login-link">Masuk di sini</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Form Validation JS -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
        });
        
        // Real-time password validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
