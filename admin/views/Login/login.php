<style>
    .loader {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .form-input:focus {
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
    }

    .btn-hover:hover {
        transform: scale(1.02);
    }

    .btn-hover:active {
        transform: scale(0.98);
    }

    .glassmorphism {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .logo-container {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.2s ease;
    }

    .password-toggle:hover {
        color: rgba(255, 255, 255, 0.9);
    }
</style>
<div class="min-h-screen overflow-hidden">
    <!-- Canvas Background -->
    <canvas id="backgroundCanvas" class="absolute inset-0 w-full h-full"></canvas>

    <!-- Login Form Container -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 logo-container rounded-3xl mb-6 shadow-2xl">
                    <!-- Shield Icon SVG -->
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">Admin Portal</h1>
                <p class="text-green-100">Secure Administrator Access</p>
            </div>

            <!-- Login Form -->
            <div class="glassmorphism rounded-3xl shadow-2xl p-8">
                <form id="loginForm" class="space-y-6">
                    <!-- Email Field -->
                    <div class="space-y-3">
                        <label for="username" class="block text-sm font-semibold text-white">
                            Administrator Username
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="username"
                                class="form-input w-full px-4 py-4 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-2xl text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-200 text-lg"
                                placeholder="Enter your admin username" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-3">
                        <label for="password" class="block text-sm font-semibold text-white">
                            Administrator Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                class="form-input w-full px-4 py-4 pr-12 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-2xl text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:border-transparent transition-all duration-200 text-lg"
                                placeholder="Enter your admin password" />
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="submitBtn"
                        class="btn-hover w-full py-4 px-6 bg-white bg-opacity-20 backdrop-blur-sm text-white font-semibold text-lg rounded-2xl hover:bg-opacity-30 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-transparent disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 border border-white border-opacity-30">
                        <span id="btnText">Access Admin Panel</span>
                        <div id="btnLoader" class="hidden flex items-center justify-center space-x-3">
                            <svg class="loader w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Authenticating...</span>
                        </div>
                    </button>
                </form>

                <!-- Security Notice -->
                <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-xl border border-white border-opacity-20">
                    <p class="text-center text-sm text-green-100 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Secure admin access protected by enterprise-grade encryption
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toastify CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<!-- Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    // Show error toast with custom close button
    function showError(message) {
        const toast = Toastify({
            text: message,
            duration: 5000,
            close: false,
            gravity: "top",
            position: "right",
            className: "error",
            stopOnFocus: true,
            onClick: function() {}
        }).showToast();

        // Add custom close button
        const toastElement = toast.toastElement;
        const closeBtn = document.createElement('button');
        closeBtn.className = 'toast-close-btn';
        closeBtn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            toastElement.style.opacity = '0';
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300);
        };
        toastElement.appendChild(closeBtn);
    }

    // Show success toast with custom close button
    function showSuccess(message) {
        const toast = Toastify({
            text: message,
            duration: 3000,
            close: false,
            gravity: "top",
            position: "right",
            className: "success",
            stopOnFocus: true,
            onClick: function() {}
        }).showToast();

        // Add custom close button
        const toastElement = toast.toastElement;
        const closeBtn = document.createElement('button');
        closeBtn.className = 'toast-close-btn';
        closeBtn.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        closeBtn.onclick = function(e) {
            e.stopPropagation();
            toastElement.style.opacity = '0';
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300);
        };
        toastElement.appendChild(closeBtn);
    }

    // Particle system and canvas animation
    class ParticleSystem {
        constructor(canvas) {
            this.canvas = canvas;
            this.ctx = canvas.getContext('2d');
            this.particles = [];
            this.particleCount = 80;
            this.colors = ['#FFFFFF', '#F8FAFC', '#F1F5F9', '#E2E8F0', '#CBD5E1'];

            this.resizeCanvas();
            this.initParticles();
            this.animate();

            window.addEventListener('resize', () => this.resizeCanvas());
        }

        resizeCanvas() {
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
        }

        initParticles() {
            this.particles = [];
            for (let i = 0; i < this.particleCount; i++) {
                this.particles.push({
                    x: Math.random() * this.canvas.width,
                    y: Math.random() * this.canvas.height,
                    vx: (Math.random() - 0.5) * 0.5,
                    vy: (Math.random() - 0.5) * 0.5,
                    size: Math.random() * 3 + 1,
                    opacity: Math.random() * 0.6 + 0.2,
                    color: this.colors[Math.floor(Math.random() * this.colors.length)]
                });
            }
        }

        animate() {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            // Create gradient background with #24A556
            const gradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
            gradient.addColorStop(0, '#24A556');
            gradient.addColorStop(0.5, '#24A556');
            gradient.addColorStop(1, '#24A556');
            this.ctx.fillStyle = gradient;
            this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

            // Update and draw particles
            this.particles.forEach(particle => {
                particle.x += particle.vx;
                particle.y += particle.vy;

                // Bounce off edges
                if (particle.x <= 0 || particle.x >= this.canvas.width) particle.vx *= -1;
                if (particle.y <= 0 || particle.y >= this.canvas.height) particle.vy *= -1;

                // Draw particle
                this.ctx.beginPath();
                this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                this.ctx.fillStyle = particle.color;
                this.ctx.globalAlpha = particle.opacity;
                this.ctx.fill();

                // Create subtle glow effect
                this.ctx.shadowColor = particle.color;
                this.ctx.shadowBlur = 10;
                this.ctx.fill();
                this.ctx.shadowBlur = 0;
            });

            // Draw connections between nearby particles
            this.particles.forEach((particle, i) => {
                this.particles.slice(i + 1).forEach(otherParticle => {
                    const dx = particle.x - otherParticle.x;
                    const dy = particle.y - otherParticle.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < 100) {
                        this.ctx.beginPath();
                        this.ctx.moveTo(particle.x, particle.y);
                        this.ctx.lineTo(otherParticle.x, otherParticle.y);
                        this.ctx.strokeStyle = `rgba(255, 255, 255, 0.4)`;
                        this.ctx.lineWidth = 1.5;
                        this.ctx.globalAlpha = 0.4;
                        this.ctx.stroke();
                    }
                });
            });

            this.ctx.globalAlpha = 1;
            requestAnimationFrame(() => this.animate());
        }
    }

    // Password toggle functionality
    class PasswordToggle {
        constructor() {
            this.passwordInput = document.getElementById('password');
            this.toggleBtn = document.getElementById('passwordToggle');
            this.eyeIcon = document.getElementById('eyeIcon');
            this.eyeOffIcon = document.getElementById('eyeOffIcon');

            this.toggleBtn.addEventListener('click', () => this.togglePassword());
        }

        togglePassword() {
            const isPassword = this.passwordInput.type === 'password';

            if (isPassword) {
                this.passwordInput.type = 'text';
                this.eyeIcon.classList.add('hidden');
                this.eyeOffIcon.classList.remove('hidden');
            } else {
                this.passwordInput.type = 'password';
                this.eyeIcon.classList.remove('hidden');
                this.eyeOffIcon.classList.add('hidden');
            }
        }
    }

    // Form handling
    class LoginForm {
        constructor() {
            this.form = document.getElementById('loginForm');
            this.usernameInput = document.getElementById('username');
            this.passwordInput = document.getElementById('password');
            this.submitBtn = document.getElementById('submitBtn');
            this.btnText = document.getElementById('btnText');
            this.btnLoader = document.getElementById('btnLoader');

            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        validateEmail(email) {
            return /\S+@\S+\.\S+/.test(email);
        }

        setLoading(loading) {
            if (loading) {
                this.submitBtn.disabled = true;
                this.btnText.classList.add('hidden');
                this.btnLoader.classList.remove('hidden');
            } else {
                this.submitBtn.disabled = false;
                this.btnText.classList.remove('hidden');
                this.btnLoader.classList.add('hidden');
            }
        }

        async handleSubmit(e) {
            e.preventDefault();

            const username = this.usernameInput.value.trim();
            const password = this.passwordInput.value.trim();

            if (!username) {
                showError('Username is required');
                return;
            }

            if (!password) {
                showError('Password is required');
                return;
            }

            if (password.length < 6) {
                showError('Password must be at least 6 characters long');
                return;
            }

            this.setLoading(true);
            const self = this;

            $.post("api/login", {
                username: username,
                password: password
            }, function(response) {
                if (response.status == "success") {
                    showSuccess(response.message);
                    window.location.href = "/super/admin";
                    // Redirect or refresh here if needed
                } else {
                    showError(response.message);
                }

                // ✅ Now this refers correctly to the LoginForm instance
                self.setLoading(false);
            }).fail(function() {
                showError("Network error. Please try again.");
                self.setLoading(false);
            });
        }
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('backgroundCanvas');
        new ParticleSystem(canvas);
        new LoginForm();
        new PasswordToggle();
    });
</script>