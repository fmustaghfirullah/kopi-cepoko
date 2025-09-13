<?php
session_start();
require_once '../config/database.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: settings/');
    exit();
}

$errorMessage = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $errorMessage = 'Username dan password wajib diisi';
    } else {
        require_once '../backend/functions/admin.php';
        $adminManager = new AdminManager();
        
        if ($adminManager->authenticate($username, $password)) {
            header('Location: settings/');
            exit();
        } else {
            $errorMessage = 'Username atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kopi Cepoko</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="logo">
                <i class="fas fa-coffee" style="font-size: 3rem; color: var(--admin-primary); margin-bottom: 1rem;"></i>
                <h2>Kopi Cepoko</h2>
                <p style="color: #666; margin-bottom: 2rem;">Admin Dashboard</p>
            </div>
            
            <?php if ($errorMessage): ?>
                <div class="alert-admin alert-danger-admin">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" class="form-control" 
                               placeholder="Masukkan username" 
                               value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" class="form-control" 
                               placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>
                
                <button type="submit" class="btn btn-admin btn-primary-admin">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>
            
            <div class="login-footer">
                <hr style="margin: 2rem 0;">
                <p style="text-align: center; color: #666; font-size: 0.875rem;">
                    <i class="fas fa-shield-alt"></i> Area terbatas hanya untuk administrator
                </p>
                <p style="text-align: center; margin-top: 1rem;">
                    <a href="../index.php" style="color: var(--admin-accent); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Website
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <style>
    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-group i {
        position: absolute;
        left: 12px;
        color: #999;
        z-index: 2;
    }
    
    .input-group .form-control {
        padding-left: 40px;
        padding-right: 40px;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        z-index: 2;
        padding: 5px;
    }
    
    .password-toggle:hover {
        color: var(--admin-accent);
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #666;
        cursor: pointer;
    }
    
    .checkbox-label input {
        margin-right: 8px;
    }
    
    .login-footer {
        margin-top: 2rem;
    }
    
    .login-form {
        animation: fadeInUp 0.6s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Security indicator */
    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--admin-primary), var(--admin-accent));
    }
    
    /* Responsive adjustments */
    @media (max-width: 480px) {
        .login-form {
            margin: 1rem;
            padding: 2rem;
        }
        
        .logo h2 {
            font-size: 1.5rem;
        }
    }
    
    /* Loading state */
    .btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 8px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    </style>
    
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            passwordIcon.className = 'fas fa-eye';
        }
    }
    
    // Form submission loading state
    document.querySelector('form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalContent = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Memverifikasi...';
        
        // Fallback to re-enable button after 10 seconds
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }, 10000);
    });
    
    // Auto-focus username field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('username').focus();
    });
    
    // Handle Enter key navigation
    document.getElementById('username').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('password').focus();
        }
    });
    
    // Security: Clear password field on page load
    window.addEventListener('pageshow', function() {
        document.getElementById('password').value = '';
    });
    </script>
</body>
</html>