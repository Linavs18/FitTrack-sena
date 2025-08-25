<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FitTrack - Crear Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Archivo CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/argon-dashboard.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/argon-dashboard.min.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/costum.css') }}?v=2">
</head>
<body class="register-page">
    <div class="auth-container">
        <div class="auth-card register-card">
            <div class="auth-header register-header">
                <h1><i class="fas fa-user-plus"></i> FitTrack</h1>
                <p>Únete a nuestra comunidad fitness</p>
            </div>
            
            <div class="auth-body">
                {{-- Mensajes de error --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>¡Oops!</strong> Hay algunos problemas con tu registro:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.register') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-1"></i> Nombre Completo
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name"
                                   placeholder="Tu nombre completo">
                            <span class="input-icon">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i> Correo Electrónico
                        </label>
                        <div class="input-group">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   placeholder="ejemplo@correo.com">
                            <span class="input-icon">
                                <i class="fas fa-at"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Mínimo 8 caracteres"
                                   onkeyup="checkPasswordStrength(this.value)">
                            <span class="input-icon">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                        <div id="password-strength" class="password-strength"></div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-1"></i> Confirmar Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Repite tu contraseña">
                            <span class="input-icon">
                                <i class="fas fa-check-double"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>
                        Crear Cuenta
                    </button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p class="mb-0">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="auth-link">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('password-strength');
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    feedback = '<span class="strength-weak"><i class="fas fa-times-circle"></i> Muy débil</span>';
                    break;
                case 2:
                    feedback = '<span class="strength-fair"><i class="fas fa-exclamation-circle"></i> Regular</span>';
                    break;
                case 3:
                    feedback = '<span class="strength-good"><i class="fas fa-check-circle"></i> Buena</span>';
                    break;
                case 4:
                    feedback = '<span class="strength-strong"><i class="fas fa-shield-alt"></i> Muy fuerte</span>';
                    break;
            }
            
            strengthDiv.innerHTML = feedback;
        }
        
        // Auto-hide alerts after 7 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 7000);
        
        // Focus animation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Real-time password confirmation validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        confirmPassword.addEventListener('keyup', function() {
            if (this.value !== password.value) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>