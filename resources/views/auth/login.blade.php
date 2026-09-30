<x-guest-layout>
    @section('title')
        Operational Dashboard SMII - Login
    @endsection

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #060913;
        }

        /* 3D Three.js Canvas */
        #threejs-login-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: auto;
        }

        /* Background Ambient Glow & Vignette */
        .login-overlay-vignette {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background: radial-gradient(circle at 75% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 25% 40%, rgba(245, 158, 11, 0.08) 0%, transparent 60%),
                radial-gradient(circle at center, rgba(6, 9, 19, 0.1) 0%, rgba(6, 9, 19, 0.75) 100%);
        }

        /* Split Screen Container */
        .login-split-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 40px 60px;
            max-width: 1440px;
            margin: 0 auto;
        }

        /* Left Side: 3D Showcase & Branding */
        .showcase-section {
            flex: 1 1 50%;
            max-width: 580px;
            padding-right: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #ffffff;
            pointer-events: none;
        }

        .showcase-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(245, 158, 11, 0.14);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: #fbbf24;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 18px;
            width: fit-content;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.15);
            backdrop-filter: blur(10px);
        }

        .showcase-badge .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #fbbf24;
            box-shadow: 0 0 10px #fbbf24;
            animation: pulseGlow 2s infinite;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.4);
                opacity: 0.5;
            }
        }

        .showcase-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin: 0 0 16px 0;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .showcase-title .gradient-text {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .showcase-desc {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 28px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        /* 3D Operational Highlights Pills */
        .showcase-highlights {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            max-width: 480px;
        }

        .highlight-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.82rem;
            font-weight: 600;
            color: #e2e8f0;
            transition: all 0.3s ease;
        }

        .highlight-pill i {
            font-size: 1rem;
        }

        /* Right Side: Glassmorphic Login Card */
        .login-card-section {
            flex: 1 1 45%;
            display: flex;
            justify-content: flex-end;
        }

        .login-glass-box {
            position: relative;
            width: 100%;
            max-width: 440px;
            background: rgba(13, 18, 36, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 28px;
            padding: 36px 32px 28px 32px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.8),
                0 0 40px rgba(99, 102, 241, 0.18),
                inset 0 1px 1px rgba(255, 255, 255, 0.25);
            animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            overflow: hidden;
        }

        /* Top Glowing Line Bar */
        .login-glass-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: linear-gradient(90deg, #f59e0b, #6366f1, #38bdf8, #10b981);
            background-size: 300% 300%;
            animation: gradientShift 6s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes floatUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Header Branding inside Card */
        .brand-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .brand-logo-container {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .brand-logo-container:hover {
            transform: scale(1.08) rotate(-4deg);
        }

        .brand-logo-container img {
            max-height: 38px;
            max-width: 38px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
        }

        .brand-text-header .card-main-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .brand-text-header .card-main-title span {
            color: #fbbf24;
        }

        .brand-text-header .card-subtitle {
            font-size: 0.8rem;
            color: #94a3b8;
            margin: 2px 0 0 0;
        }

        /* Form Inputs */
        .form-group-custom {
            margin-bottom: 18px;
        }

        .custom-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-icon-wrapper {
            position: relative;
            width: 100%;
        }

        .input-icon-wrapper .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .input-icon-wrapper input {
            width: 100%;
            padding: 13px 44px 13px 46px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 0.92rem;
            font-family: inherit;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-icon-wrapper input:focus {
            background: rgba(15, 23, 42, 0.95);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2), 0 4px 16px rgba(99, 102, 241, 0.2);
        }

        .input-icon-wrapper input:focus~.field-icon {
            color: #818cf8;
        }

        .input-icon-wrapper input::placeholder {
            color: #475569;
        }

        .toggle-password-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password-btn:hover {
            color: #cbd5e1;
        }

        /* Checkbox & Forgot Password */
        .form-row-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 0.84rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            accent-color: #6366f1;
            width: 16px;
            height: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-link {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }

        .forgot-link:hover {
            color: #a5b4fc;
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-login-submit {
            position: relative;
            width: 100%;
            padding: 13px 24px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #d97706 100%);
            background-size: 200% 200%;
            color: #ffffff;
            font-size: 0.98rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-login-submit:hover {
            background-position: 100% 100%;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(217, 119, 6, 0.4), 0 0 20px rgba(99, 102, 241, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        .btn-login-submit i {
            transition: transform 0.3s ease;
        }

        .btn-login-submit:hover i {
            transform: translateX(4px);
        }

        /* Footer Info */
        .login-card-footer {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.76rem;
            color: #64748b;
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #10b981;
            font-weight: 600;
            background: rgba(16, 185, 129, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-pill .live-indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 6px #10b981;
        }

        /* Error styling */
        .error-message {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .login-split-container {
                flex-direction: column;
                justify-content: center;
                gap: 36px;
                padding: 30px 24px;
            }

            .showcase-section {
                max-width: 100%;
                padding-right: 0;
                text-align: center;
                align-items: center;
            }

            .showcase-title {
                font-size: 2.2rem;
            }

            .login-card-section {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .showcase-section {
                display: none;
            }

            .login-split-container {
                padding: 20px 16px;
            }

            .login-glass-box {
                padding: 28px 20px 22px 20px;
                border-radius: 22px;
            }
        }
    </style>

    <!-- 3D Three.js Background Canvas -->
    <canvas id="threejs-login-bg"></canvas>
    <div class="login-overlay-vignette"></div>

    <!-- Main Split-Screen Container -->
    <div class="login-split-container">
        <!-- Left Side: 3D Showcase & Enterprise Information -->
        <div class="showcase-section">
            <div class="showcase-badge">
                <span class="pulse-dot"></span>
                Integrated Operational Platform
            </div>

            <h1 class="showcase-title">
                SMII <span class="gradient-text">Enterprise</span><br>
                Operational Portal
            </h1>

            <p class="showcase-desc">
                Sistem monitoring operasional terpadu PT. Sinar Meadow International Indonesia. Pantau produksi,
                warehouse, e-commerce, sales, dan HSE secara real-time.
            </p>

            <!-- Feature Pills -->
            <div class="showcase-highlights">
                <div class="highlight-pill">
                    <i class="fa-solid fa-chart-pie text-sky-400"></i>
                    <span>Real-Time Analytics</span>
                </div>
                <div class="highlight-pill">
                    <i class="fa-solid fa-warehouse text-amber-400"></i>
                    <span>Smart Warehouse</span>
                </div>
                <div class="highlight-pill">
                    <i class="fa-solid fa-industry text-indigo-400"></i>
                    <span>Production Telemetry</span>
                </div>
                <div class="highlight-pill">
                    <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                    <span>HSE & Safety Board</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Clean Login Card -->
        <div class="login-card-section">
            <div class="login-glass-box">
                <!-- Brand Header inside Card -->
                <div class="brand-header">
                    <div class="brand-logo-container">
                        <img src="{{ asset('assets/images/logo/sinarmeadow.png') }}" alt="Sinar Meadow Logo"
                            onerror="this.src='{{ asset('assets/images/logo/smii.png') }}'">
                    </div>
                    <div class="brand-text-header">
                        <h2 class="card-main-title">Portal <span>Login</span></h2>
                        <p class="card-subtitle">Operational Dashboard SMII</p>
                    </div>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="mb-4 text-emerald-400 text-sm text-center" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- NIK Field -->
                    <div class="form-group-custom">
                        <label for="nik-input" class="custom-label">NIK (Nomor Induk Karyawan)</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-id-card field-icon"></i>
                            <input type="text" name="nik" id="nik-input" value="{{ old('nik') }}"
                                placeholder="Masukkan NIK Anda" required autofocus autocomplete="username">
                        </div>
                        @if ($errors->has('nik'))
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                {{ $errors->first('nik') }}
                            </div>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div class="form-group-custom">
                        <label for="password-input" class="custom-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" name="password" id="password-input" placeholder="••••••••••••"
                                required autocomplete="current-password">
                            <button type="button" class="toggle-password-btn" id="togglePasswordBtn"
                                onclick="toggleLoginPassword()" aria-label="Toggle Password Visibility">
                                <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <!-- Options Row (Remember me + Forgot Password) -->
                    <div class="form-row-options">
                        <label class="checkbox-label" for="remember-me">
                            <input type="checkbox" name="remember" id="remember-me">
                            <span>Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login-submit">
                        <span>Masuk Dashboard</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Card Footer Status -->
                <div class="login-card-footer">
                    <div class="status-pill">
                        <span class="live-indicator"></span>
                        SYSTEM ONLINE
                    </div>
                    <div>&copy; {{ date('Y') }} PT. Sinar Meadow | MIS</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Three.js CDN Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Interactive 3D Three.js Animation Script -->
    <script>
        // Toggle password visibility helper
        function toggleLoginPassword() {
            const pwd = document.getElementById('password-input');
            const icon = document.getElementById('togglePasswordIcon');
            if (!pwd || !icon) return;
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Initialize 3D Operational Holographic Scene
        (function initThreeJsBackground() {
            const canvas = document.getElementById('threejs-login-bg');
            if (!canvas || typeof THREE === 'undefined') return;

            const scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0x060913, 0.0015);

            const camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 1, 3000);
            camera.position.z = 520;

            const renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                antialias: true,
                alpha: true
            });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Mouse interaction tracker with lerp
            const mouse = {
                x: 0,
                y: 0,
                targetX: 0,
                targetY: 0
            };
            window.addEventListener('mousemove', (e) => {
                mouse.targetX = (e.clientX - window.innerWidth / 2) * 0.4;
                mouse.targetY = (e.clientY - window.innerHeight / 2) * 0.4;
            }, {
                passive: true
            });

            // 1. Interactive 3D Particle Constellation (Floating Across Scene)
            const particleCount = 1200;
            const particleGeo = new THREE.BufferGeometry();
            const positions = new Float32Array(particleCount * 3);
            const colors = new Float32Array(particleCount * 3);

            const colorPalette = [
                new THREE.Color(0xf59e0b), // Amber Gold
                new THREE.Color(0x6366f1), // Indigo
                new THREE.Color(0x38bdf8), // Cyan Blue
                new THREE.Color(0x10b981) // Emerald
            ];

            for (let i = 0; i < particleCount; i++) {
                const i3 = i * 3;
                positions[i3] = (Math.random() - 0.5) * 1800;
                positions[i3 + 1] = (Math.random() - 0.5) * 1200;
                positions[i3 + 2] = (Math.random() - 0.5) * 1000;

                const c = colorPalette[Math.floor(Math.random() * colorPalette.length)];
                colors[i3] = c.r;
                colors[i3 + 1] = c.g;
                colors[i3 + 2] = c.b;
            }

            particleGeo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            particleGeo.setAttribute('color', new THREE.BufferAttribute(colors, 3));

            function createCircleTexture() {
                const c = document.createElement('canvas');
                c.width = 64;
                c.height = 64;
                const ctx = c.getContext('2d');
                const grad = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
                grad.addColorStop(0, 'rgba(255,255,255,1)');
                grad.addColorStop(0.3, 'rgba(255,255,255,0.85)');
                grad.addColorStop(0.7, 'rgba(255,255,255,0.2)');
                grad.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = grad;
                ctx.fillRect(0, 0, 64, 64);
                const t = new THREE.Texture(c);
                t.needsUpdate = true;
                return t;
            }

            const particleMat = new THREE.PointsMaterial({
                size: 6,
                vertexColors: true,
                map: createCircleTexture(),
                transparent: true,
                opacity: 0.85,
                blending: THREE.AdditiveBlending,
                depthWrite: false
            });

            const particleSystem = new THREE.Points(particleGeo, particleMat);
            scene.add(particleSystem);

            // 2. Central Holographic Core (Positioned on the Left Side so it is NOT covered by the Card)
            const ringGroup = new THREE.Group();

            // Outer Glowing Indigo Torus
            const torusGeo1 = new THREE.TorusGeometry(150, 1.4, 16, 90);
            const torusMat1 = new THREE.MeshBasicMaterial({
                color: 0x6366f1,
                wireframe: true,
                transparent: true,
                opacity: 0.45,
                blending: THREE.AdditiveBlending
            });
            const torus1 = new THREE.Mesh(torusGeo1, torusMat1);
            ringGroup.add(torus1);

            // Middle Amber Gold Torus
            const torusGeo2 = new THREE.TorusGeometry(115, 1.2, 16, 70);
            const torusMat2 = new THREE.MeshBasicMaterial({
                color: 0xf59e0b,
                wireframe: true,
                transparent: true,
                opacity: 0.55,
                blending: THREE.AdditiveBlending
            });
            const torus2 = new THREE.Mesh(torusGeo2, torusMat2);
            torus2.rotation.x = Math.PI / 3;
            ringGroup.add(torus2);

            // Inner Cyan Holographic Icosahedron
            const icoGeo = new THREE.IcosahedronGeometry(68, 1);
            const icoMat = new THREE.MeshBasicMaterial({
                color: 0x38bdf8,
                wireframe: true,
                transparent: true,
                opacity: 0.4,
                blending: THREE.AdditiveBlending
            });
            const ico = new THREE.Mesh(icoGeo, icoMat);
            ringGroup.add(ico);

            // Inner Core Glowing Sphere
            const sphereGeo = new THREE.SphereGeometry(26, 24, 24);
            const sphereMat = new THREE.MeshBasicMaterial({
                color: 0xfbbf24,
                wireframe: true,
                transparent: true,
                opacity: 0.6,
                blending: THREE.AdditiveBlending
            });
            const sphere = new THREE.Mesh(sphereGeo, sphereMat);
            ringGroup.add(sphere);

            // Position 3D core dynamically: on desktop position on the left showcase side
            function updateCorePosition() {
                if (window.innerWidth > 1024) {
                    ringGroup.position.set(-window.innerWidth * 0.2, 0, 0);
                } else {
                    ringGroup.position.set(0, window.innerHeight * 0.15, -120);
                }
            }
            updateCorePosition();
            scene.add(ringGroup);

            // 3. Cyber Grid Floor Wave
            const gridGeo = new THREE.PlaneGeometry(1800, 1800, 36, 36);
            const gridMat = new THREE.MeshBasicMaterial({
                color: 0x3b82f6,
                wireframe: true,
                transparent: true,
                opacity: 0.12,
                blending: THREE.AdditiveBlending
            });
            const grid = new THREE.Mesh(gridGeo, gridMat);
            grid.rotation.x = -Math.PI / 2.2;
            grid.position.y = -240;
            grid.position.z = -150;
            scene.add(grid);

            // Responsive Window Resize Handling
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
                updateCorePosition();
            });

            // Animation Loop
            let clock = new THREE.Clock();

            function animate() {
                requestAnimationFrame(animate);

                const elapsed = clock.getElapsedTime();

                // Smooth mouse parallax lerp
                mouse.x += (mouse.targetX - mouse.x) * 0.05;
                mouse.y += (mouse.targetY - mouse.y) * 0.05;

                camera.position.x = mouse.x * 0.35;
                camera.position.y = -mouse.y * 0.35;
                camera.lookAt(0, 0, 0);

                // Rotate Particle Constellation
                particleSystem.rotation.y = elapsed * 0.03;
                particleSystem.rotation.x = Math.sin(elapsed * 0.02) * 0.04;

                // Animate Holographic Rings
                torus1.rotation.x = elapsed * 0.22;
                torus1.rotation.y = elapsed * 0.16;
                torus2.rotation.y = -elapsed * 0.28;
                torus2.rotation.z = elapsed * 0.2;
                ico.rotation.x = elapsed * 0.15;
                ico.rotation.y = elapsed * 0.22;
                sphere.rotation.y = elapsed * 0.4;

                // Grid undulating wave effect
                const pos = gridGeo.attributes.position;
                for (let i = 0; i < pos.count; i++) {
                    const u = pos.getX(i);
                    const v = pos.getY(i);
                    const z = Math.sin(u * 0.01 + elapsed * 1.5) * Math.cos(v * 0.01 + elapsed * 1.2) * 20;
                    pos.setZ(i, z);
                }
                gridGeo.attributes.position.needsUpdate = true;

                renderer.render(scene, camera);
            }

            animate();
        })();
    </script>
</x-guest-layout>
