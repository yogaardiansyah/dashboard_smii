<x-app-layout>
    @section('title')
        Operational Dashboard SMII
    @endsection

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body,
        html {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.4s ease;
        }

        /* Default Bright Base (Light Mode) */
        html,
        body,
        body .wrapper,
        body .content-wrapper,
        .dashboard-container {
            background-color: #f8fafc !important;
            background: linear-gradient(135deg, #f8fafc 0%, #edf4ff 40%, #f5f3ff 75%, #f1f5f9 100%) !important;
            color: #0f172a !important;
        }

        .dashboard-container {
            position: relative;
            min-height: 100vh;
            padding-bottom: 60px;
            z-index: 1;
        }

        /* Three.js Background Canvas */
        #threejs-dashboard-bg {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
            opacity: 0.85;
            transition: opacity 0.4s ease;
        }

        /* Ambient Background Overlay Pattern */
        .ambient-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
            background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(245, 158, 11, 0.05) 0%, transparent 40%);
        }

        /* Glass Header Bar */
        .glass-header {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin: 28px 40px 0 40px;
            padding: 22px 32px;
            background: rgba(255, 255, 255, 0.88) !important;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            border-radius: 26px;
            box-shadow: 0 16px 40px -10px rgba(99, 102, 241, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 1) !important;
            animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.4s ease;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar-glow-wrapper {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-glow-ring {
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #6366f1, #38bdf8);
            animation: rotateGlow 6s linear infinite;
            filter: blur(3px);
            opacity: 0.85;
        }

        .avatar-box {
            position: relative;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #ffffff !important;
            border: 2px solid #e0e7ff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6em;
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.15) !important;
            z-index: 2;
            transition: all 0.4s ease;
        }

        .status-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 13px;
            height: 13px;
            background-color: #10b981;
            border: 2.5px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
            z-index: 3;
        }

        .user-text-info .greeting-sub {
            font-size: 0.82em;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6366f1 !important;
            font-weight: 700 !important;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.4s ease;
        }

        .user-text-info .user-name-gradient {
            font-size: 1.55em;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b 0%, #4338ca 60%, #b45309 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            line-height: 1.2;
            transition: all 0.4s ease;
        }

        /* Header Controls Area (Clock + Theme Switcher) */
        .header-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 16px;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #1e293b !important;
            font-size: 0.88em;
            font-weight: 700;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.08) !important;
        }

        .theme-toggle-btn:hover {
            transform: translateY(-2px);
            background: #f8fafc !important;
            border-color: #94a3b8 !important;
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.16) !important;
        }

        /* Clock Widget */
        .clock-widget-container {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            padding: 10px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.08) !important;
            transition: all 0.4s ease;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75em;
            font-weight: 800;
            color: #059669;
            letter-spacing: 1px;
            background: rgba(16, 185, 129, 0.12);
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid rgba(52, 211, 153, 0.4);
        }

        .live-indicator .pulse-circle {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            animation: pingPulse 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        .clock-display-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.05em;
            font-weight: 700;
            color: #1e293b !important;
            letter-spacing: 0.5px;
            transition: color 0.4s ease;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            position: relative;
            z-index: 10;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            padding: 36px 40px;
        }

        /* Pure Glass Cards */
        .dashboard-card {
            position: relative;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.95) !important;
            border-radius: 26px;
            padding: 32px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.06), 0 2px 6px rgba(0, 0, 0, 0.02) !important;
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Top Glowing Line Bar per Theme */
        .card-glow-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: var(--card-gradient);
            opacity: 0.9;
            transition: opacity 0.3s, height 0.3s;
        }

        /* Shimmer Overlay effect */
        .card-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0) 50%, rgba(255, 255, 255, 0.5) 100%);
            pointer-events: none;
            transition: opacity 0.4s;
            opacity: 0.5;
        }

        .dashboard-card:hover {
            transform: translateY(-8px) scale(1.015);
            border-color: rgba(199, 210, 254, 1) !important;
            box-shadow: 0 24px 50px -10px var(--card-glow-shadow), 0 8px 24px rgba(99, 102, 241, 0.12) !important;
            background: #ffffff !important;
        }

        .dashboard-card:hover .card-glow-bar {
            opacity: 1;
            height: 5px;
        }

        /* Top Badge Tag inside Card */
        .card-header-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .card-icon-wrapper {
            position: relative;
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2em;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.4s, border-color 0.4s;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03) !important;
        }

        .dashboard-card:hover .card-icon-wrapper {
            transform: scale(1.12) rotate(-6deg);
            background: var(--icon-bg-hover) !important;
            border-color: var(--icon-border-hover) !important;
            box-shadow: 0 8px 20px var(--card-glow-shadow) !important;
        }

        .tag-badge {
            font-size: 0.7em;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 20px;
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            transition: all 0.3s;
        }

        .dashboard-card:hover .tag-badge {
            background: var(--tag-bg-hover) !important;
            color: #ffffff !important;
            border-color: transparent !important;
        }

        /* Card Content Text */
        .card-body-content {
            margin-bottom: 24px;
            width: 100%;
        }

        .card-title {
            font-size: 1.42em;
            font-weight: 800 !important;
            color: #0f172a !important;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .card-desc {
            font-size: 0.92em;
            line-height: 1.55;
            color: #64748b !important;
            font-weight: 500 !important;
            transition: color 0.3s;
        }

        /* Action Button */
        .card-footer-action {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: auto;
        }

        .btn-glass-action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            border-radius: 14px;
            font-size: 0.9em;
            font-weight: 700;
            color: #ffffff !important;
            background: var(--btn-gradient);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 15px var(--card-glow-shadow);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-glass-action .btn-arrow {
            transition: transform 0.3s ease;
            font-size: 0.9em;
        }

        .dashboard-card:hover .btn-glass-action {
            padding-right: 26px;
            box-shadow: 0 6px 20px var(--card-glow-shadow);
            border-color: rgba(255, 255, 255, 0.6);
        }

        .dashboard-card:hover .btn-glass-action .btn-arrow {
            transform: translateX(4px);
        }

        /* Modal Styling */
        .modal-overlay {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(15, 23, 42, 0.65) !important;
            transition: opacity 0.3s ease, background 0.4s ease;
        }

        .modal-glass-content {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 28px !important;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.35) !important;
            animation: modalPop 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.4s ease;
        }

        .modal-glass-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
            border-bottom: none !important;
            padding: 22px 30px !important;
            color: #ffffff !important;
            transition: all 0.4s ease;
        }

        .modal-glass-header h3 {
            color: #ffffff !important;
        }

        .modal-glass-header .close-modal {
            color: #ffffff !important;
        }

        .modal-glass-header .close-modal:hover {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .modal-glass-footer {
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0 !important;
            padding: 18px 30px !important;
            transition: all 0.4s ease;
        }

        .modal-glass-footer .close-modal {
            background: #e2e8f0 !important;
            color: #334155 !important;
            border: 1px solid #cbd5e1 !important;
        }

        .modal-glass-footer .close-modal:hover {
            background: #cbd5e1 !important;
            color: #0f172a !important;
        }

        /* ========================================================= */
        /* DARK MODE OVERRIDES (.dark-mode)                          */
        /* ========================================================= */
        body.dark-mode,
        body.dark-mode .wrapper,
        body.dark-mode .content-wrapper,
        body.dark-mode .dashboard-container {
            background-color: #060913 !important;
            background: #060913 !important;
            color: #f8fafc !important;
        }

        body.dark-mode .glass-header {
            background: rgba(13, 18, 36, 0.78) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6) !important;
        }

        body.dark-mode .user-text-info .greeting-sub {
            color: #a5b4fc !important;
        }

        body.dark-mode .user-text-info .user-name-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 50%, #fbbf24 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
        }

        body.dark-mode .avatar-box {
            background: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        body.dark-mode .theme-toggle-btn {
            background: rgba(30, 41, 59, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #f1f5f9 !important;
        }

        body.dark-mode .clock-widget-container {
            background: rgba(15, 23, 42, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-mode .clock-display-text {
            color: #f8fafc !important;
        }

        body.dark-mode .dashboard-card {
            background: rgba(13, 18, 36, 0.65) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4) !important;
        }

        body.dark-mode .dashboard-card:hover {
            background: rgba(20, 27, 52, 0.85) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 22px 45px -10px var(--card-glow-shadow), 0 0 25px rgba(255, 255, 255, 0.05) !important;
        }

        body.dark-mode .card-title {
            color: #ffffff !important;
        }

        body.dark-mode .card-desc {
            color: #94a3b8 !important;
        }

        body.dark-mode .tag-badge {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #cbd5e1 !important;
        }

        body.dark-mode .card-icon-wrapper {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        body.dark-mode .modal-glass-content {
            background: rgba(13, 18, 36, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        body.dark-mode .modal-glass-header {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-mode .modal-glass-footer {
            background: rgba(15, 23, 42, 0.85) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        body.dark-mode .modal-glass-footer .close-modal {
            background: #1e293b !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes rotateGlow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pingPulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Stagger Delays for Grid Cards */
        .dashboard-grid>div:nth-child(1) {
            animation-delay: 0.04s;
        }

        .dashboard-grid>div:nth-child(2) {
            animation-delay: 0.08s;
        }

        .dashboard-grid>div:nth-child(3) {
            animation-delay: 0.12s;
        }

        .dashboard-grid>div:nth-child(4) {
            animation-delay: 0.16s;
        }

        .dashboard-grid>div:nth-child(5) {
            animation-delay: 0.20s;
        }

        .dashboard-grid>div:nth-child(6) {
            animation-delay: 0.24s;
        }

        .dashboard-grid>div:nth-child(7) {
            animation-delay: 0.28s;
        }

        .dashboard-grid>div:nth-child(8) {
            animation-delay: 0.32s;
        }

        @media (max-width: 768px) {
            .glass-header {
                margin: 16px;
                padding: 18px 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .header-controls {
                width: 100%;
                justify-content: space-between;
            }

            .dashboard-grid {
                padding: 20px 16px;
                gap: 20px;
            }

            .dashboard-card {
                padding: 24px 20px;
            }
        }
    </style>

    <div class="dashboard-container">
        <!-- 3D Three.js Ambient Background Canvas -->
        <canvas id="threejs-dashboard-bg"></canvas>
        <div class="ambient-bg"></div>

        <!-- Glass Header Bar -->
        <div class="glass-header">
            <div class="user-profile-badge">
                <div class="avatar-glow-wrapper">
                    <div class="avatar-glow-ring"></div>
                    <div class="avatar-box">👑</div>
                    <div class="status-dot"></div>
                </div>
                <div class="user-text-info">
                    <div class="greeting-sub">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        Selamat Datang Kembali,
                    </div>
                    <div class="user-name-gradient">{{ Auth::user()->name }}</div>
                </div>
            </div>

            <div class="header-controls">
                <!-- Theme Toggle Button (Light/Dark Switcher) -->
                <button id="theme-toggle-btn" class="theme-toggle-btn" onclick="toggleDashboardTheme()"
                    title="Beralih Mode Terang / Gelap">
                    <span class="theme-btn-icon" id="theme-btn-icon">☀️</span>
                    <span class="theme-btn-text" id="theme-btn-text">Light Mode</span>
                </button>

                <!-- Clock Widget -->
                <div class="clock-widget-container">
                    <div class="live-indicator">
                        <span class="pulse-circle"></span>
                        SYSTEM OPERATIONAL
                    </div>
                    <div class="clock-display-text" id="admin-clock">--:--:--</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Cards Grid -->
        <div class="dashboard-grid">
            <!-- 1. Production Card -->
            <div class="dashboard-card"
                style="--card-gradient: linear-gradient(90deg, #0284c7, #6366f1);
                       --card-glow-shadow: rgba(2, 132, 199, 0.25);
                       --icon-bg-hover: rgba(2, 132, 199, 0.15);
                       --icon-border-hover: rgba(2, 132, 199, 0.4);
                       --tag-bg-hover: rgba(2, 132, 199, 0.9);
                       --btn-gradient: linear-gradient(135deg, #0284c7 0%, #4f46e5 100%);"
                onclick="window.location.href='{{ url('dashboard/dashboard-production') }}'">
                <div class="card-glow-bar"></div>
                <div class="card-shimmer"></div>

                <div class="card-header-badge">
                    <div class="card-icon-wrapper">🏭</div>
                    <span class="tag-badge">Real-Time</span>
                </div>
                <div class="card-body-content">
                    <div class="card-title">Production</div>
                    <div class="card-desc">Monitor production process, efficiency, and machine performance in real-time.
                    </div>
                </div>
                <div class="card-footer-action">
                    <button class="btn-glass-action">
                        <span>Go Dashboard</span>
                        <i class="fa-solid fa-arrow-right btn-arrow"></i>
                    </button>
                </div>
            </div>

            <!-- 2. Sales & Marketing Card -->
            <div class="dashboard-card"
                style="--card-gradient: linear-gradient(90deg, #10b981, #059669);
                       --card-glow-shadow: rgba(16, 185, 129, 0.25);
                       --icon-bg-hover: rgba(16, 185, 129, 0.15);
                       --icon-border-hover: rgba(16, 185, 129, 0.4);
                       --tag-bg-hover: rgba(16, 185, 129, 0.9);
                       --btn-gradient: linear-gradient(135deg, #059669 0%, #047857 100%);"
                onclick="window.location.href='{{ url('/dashboard-sales') }}'">
                <div class="card-glow-bar"></div>
                <div class="card-shimmer"></div>

                <div class="card-header-badge">
                    <div class="card-icon-wrapper">💼</div>
                    <span class="tag-badge">Analytics</span>
                </div>
                <div class="card-body-content">
                    <div class="card-title">Sales & Marketing</div>
                    <div class="card-desc">Analyze sales, targets, and team performance with dynamic charts.</div>
                </div>
                <div class="card-footer-action">
                    <button class="btn-glass-action">
                        <span>Go Analytics</span>
                        <i class="fa-solid fa-arrow-right btn-arrow"></i>
                    </button>
                </div>
            </div>

            <!-- 3. Warehouse Card -->
            <div class="dashboard-card" id="warehouse-card"
                style="--card-gradient: linear-gradient(90deg, #f59e0b, #ea580c);
                       --card-glow-shadow: rgba(245, 158, 11, 0.25);
                       --icon-bg-hover: rgba(245, 158, 11, 0.15);
                       --icon-border-hover: rgba(245, 158, 11, 0.4);
                       --tag-bg-hover: rgba(245, 158, 11, 0.9);
                       --btn-gradient: linear-gradient(135deg, #d97706 0%, #ea580c 100%);"
                onclick="window.location.href='{{ url('dashboard-warehouse') }}'">
                <div class="card-glow-bar"></div>
                <div class="card-shimmer"></div>

                <div class="card-header-badge">
                    <div class="card-icon-wrapper">🏬</div>
                    <span class="tag-badge">Stock Control</span>
                </div>
                <div class="card-body-content">
                    <div class="card-title">Warehouse</div>
                    <div class="card-desc">Manage stock, item movement, and inventory status with interactive visuals.</div>
                </div>
                <div class="card-footer-action">
                    <button class="btn-glass-action">
                        <span>Go Warehouse</span>
                        <i class="fa-solid fa-arrow-right btn-arrow"></i>
                    </button>
                </div>
            </div>

            <!-- 4. Safety Board Card -->
            <div class="dashboard-card"
                style="--card-gradient: linear-gradient(90deg, #ef4444, #f97316);
                       --card-glow-shadow: rgba(239, 68, 68, 0.25);
                       --icon-bg-hover: rgba(239, 68, 68, 0.15);
                       --icon-border-hover: rgba(239, 68, 68, 0.4);
                       --tag-bg-hover: rgba(239, 68, 68, 0.9);
                       --btn-gradient: linear-gradient(135deg, #dc2626 0%, #ea580c 100%);"
                onclick="window.location.href='{{ url('/dashboard/safety-board') }}'">
                <div class="card-glow-bar"></div>
                <div class="card-shimmer"></div>

                <div class="card-header-badge">
                    <div class="card-icon-wrapper">🦺</div>
                    <span class="tag-badge">Safety & Weather</span>
                </div>
                <div class="card-body-content">
                    <div class="card-title">Safety Board</div>
                    <div class="card-desc">Monitor safety records, incident prevention, and work weather visuals.</div>
                </div>
                <div class="card-footer-action">
                    <button class="btn-glass-action">
                        <span>Go Safety</span>
                        <i class="fa-solid fa-arrow-right btn-arrow"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Three.js CDN Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Interactive 3D Three.js Ambient Background for Dashboard -->
    <script>
        (function initDashboardThreeBg() {
            const canvas = document.getElementById('threejs-dashboard-bg');
            if (!canvas || typeof THREE === 'undefined') return;

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 1, 3000);
            camera.position.z = 600;

            const renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                antialias: true,
                alpha: true
            });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Mouse tracking with parallax
            const mouse = {
                x: 0,
                y: 0,
                targetX: 0,
                targetY: 0
            };
            window.addEventListener('mousemove', (e) => {
                mouse.targetX = (e.clientX - window.innerWidth / 2) * 0.35;
                mouse.targetY = (e.clientY - window.innerHeight / 2) * 0.35;
            }, {
                passive: true
            });

            // 1. Floating Operational Node Particles
            const count = 900;
            const geo = new THREE.BufferGeometry();
            const pos = new Float32Array(count * 3);
            const cols = new Float32Array(count * 3);

            function getThemeColors() {
                const isDark = document.body.classList.contains('dark-mode');
                if (isDark) {
                    return [
                        new THREE.Color(0xf59e0b), // Amber SMII
                        new THREE.Color(0x6366f1), // Indigo
                        new THREE.Color(0x38bdf8), // Cyan
                        new THREE.Color(0x10b981) // Emerald
                    ];
                } else {
                    return [
                        new THREE.Color(0x3b82f6), // Vibrant Blue
                        new THREE.Color(0x6366f1), // Indigo
                        new THREE.Color(0xd97706), // Warm Amber
                        new THREE.Color(0x8b5cf6) // Violet
                    ];
                }
            }

            const palette = getThemeColors();

            for (let i = 0; i < count; i++) {
                const i3 = i * 3;
                pos[i3] = (Math.random() - 0.5) * 2000;
                pos[i3 + 1] = (Math.random() - 0.5) * 1600;
                pos[i3 + 2] = (Math.random() - 0.5) * 1200;

                const c = palette[Math.floor(Math.random() * palette.length)];
                cols[i3] = c.r;
                cols[i3 + 1] = c.g;
                cols[i3 + 2] = c.b;
            }

            geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
            geo.setAttribute('color', new THREE.BufferAttribute(cols, 3));

            function createNodeTexture() {
                const c = document.createElement('canvas');
                c.width = 64;
                c.height = 64;
                const ctx = c.getContext('2d');
                const grad = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
                grad.addColorStop(0, 'rgba(255,255,255,1)');
                grad.addColorStop(0.3, 'rgba(255,255,255,0.75)');
                grad.addColorStop(0.7, 'rgba(255,255,255,0.15)');
                grad.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = grad;
                ctx.fillRect(0, 0, 64, 64);
                const t = new THREE.Texture(c);
                t.needsUpdate = true;
                return t;
            }

            const mat = new THREE.PointsMaterial({
                size: 5.5,
                vertexColors: true,
                map: createNodeTexture(),
                transparent: true,
                opacity: 0.75,
                blending: THREE.AdditiveBlending,
                depthWrite: false
            });

            const particles = new THREE.Points(geo, mat);
            scene.add(particles);

            // 2. Geometric Connecting Constellation
            const constelGroup = new THREE.Group();
            const polyGeo1 = new THREE.IcosahedronGeometry(220, 1);
            const polyMat1 = new THREE.MeshBasicMaterial({
                color: 0x6366f1,
                wireframe: true,
                transparent: true,
                opacity: 0.08,
                blending: THREE.AdditiveBlending
            });
            const mesh1 = new THREE.Mesh(polyGeo1, polyMat1);
            mesh1.position.set(-350, 150, -200);
            constelGroup.add(mesh1);

            const polyGeo2 = new THREE.TorusGeometry(180, 1.2, 16, 80);
            const polyMat2 = new THREE.MeshBasicMaterial({
                color: 0xf59e0b,
                wireframe: true,
                transparent: true,
                opacity: 0.1,
                blending: THREE.AdditiveBlending
            });
            const mesh2 = new THREE.Mesh(polyGeo2, polyMat2);
            mesh2.position.set(380, -120, -150);
            constelGroup.add(mesh2);

            scene.add(constelGroup);

            // Handle Resize
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });

            // Expose color updater on theme switch
            window.__updateThreeThemeColors = function() {
                const newPal = getThemeColors();
                const colorsArr = geo.attributes.color.array;
                for (let i = 0; i < count; i++) {
                    const i3 = i * 3;
                    const c = newPal[Math.floor(Math.random() * newPal.length)];
                    colorsArr[i3] = c.r;
                    colorsArr[i3 + 1] = c.g;
                    colorsArr[i3 + 2] = c.b;
                }
                geo.attributes.color.needsUpdate = true;
            };

            // Animation Loop
            let clock = new THREE.Clock();

            function animate() {
                requestAnimationFrame(animate);

                const elapsed = clock.getElapsedTime();

                // Parallax smoothing
                mouse.x += (mouse.targetX - mouse.x) * 0.04;
                mouse.y += (mouse.targetY - mouse.y) * 0.04;

                camera.position.x = mouse.x * 0.4;
                camera.position.y = -mouse.y * 0.4;
                camera.lookAt(0, 0, 0);

                particles.rotation.y = elapsed * 0.025;
                particles.rotation.x = Math.sin(elapsed * 0.015) * 0.04;

                mesh1.rotation.x = elapsed * 0.08;
                mesh1.rotation.y = elapsed * 0.12;

                mesh2.rotation.y = -elapsed * 0.1;
                mesh2.rotation.z = elapsed * 0.08;

                renderer.render(scene, camera);
            }

            animate();
        })();
    </script>

    <!-- Theme Switcher Script -->
    <script>
        function applyTheme(theme) {
            const icon = document.getElementById('theme-btn-icon');
            const text = document.getElementById('theme-btn-text');
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
                if (icon) icon.textContent = '🌙';
                if (text) text.textContent = 'Dark Mode';
            } else {
                document.body.classList.remove('dark-mode');
                document.body.classList.add('light-mode');
                if (icon) icon.textContent = '☀️';
                if (text) text.textContent = 'Light Mode';
            }
            if (typeof window.__updateThreeThemeColors === 'function') {
                window.__updateThreeThemeColors();
            }
        }

        function toggleDashboardTheme() {
            const isDark = document.body.classList.contains('dark-mode');
            const newTheme = isDark ? 'light' : 'dark';
            localStorage.setItem('dashboard_smii_theme', newTheme);
            applyTheme(newTheme);
        }

        (function() {
            const savedTheme = localStorage.getItem('dashboard_smii_theme') || 'light';
            applyTheme(savedTheme);
        })();
    </script>

    <!-- Clock Script -->
    <script>
        function updateClock() {
            var now = new Date();
            var options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            var dateStr = now.toLocaleDateString('id-ID', options);
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            var timeStr = h + ':' + m + ':' + s;
            var el = document.getElementById('admin-clock');
            if (el) {
                el.textContent = dateStr + ' | ' + timeStr;
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    <!-- Modal Control Functions -->
    <script>
        function openModal(modalId = 'modal-employee') {
            const m = document.getElementById(modalId);
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modalId = 'modal-employee') {
            const m = document.getElementById(modalId);
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function closeAllModals() {
            document.querySelectorAll('.modal-overlay').forEach(function(m) {
                if (m.classList.contains('flex') && !m.classList.contains('hidden')) {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                }
            });
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.close-modal').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeModal(btn.dataset.modal || 'modal-employee');
                });
            });

            document.querySelectorAll('.modal-overlay').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeAllModals();
            });
        });
    </script>

</x-app-layout>
