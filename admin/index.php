<?php
session_start();
require_once '../config/config.php';
require_once '../config/functions.php'; 

// Check if user is admin
if (!isAdmin()) {
    header("Location: ../staff/dashboard.php");
    exit();
}

// Get comprehensive statistics
$stats = [];

// Users statistics
$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
$stats['users_count'] = $stmt->fetch_assoc()['total'];

$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stats['new_users_month'] = $stmt->fetch_assoc()['total'];

// Tasks statistics
$stmt = $conn->query("SELECT COUNT(*) as total FROM tasks");
$stats['tasks_count'] = $stmt->fetch_assoc()['total'] ?? 0;

// Submissions statistics
$stmt = $conn->query("SELECT COUNT(*) as total FROM submissions");
$stats['submissions_count'] = $stmt->fetch_assoc()['total'] ?? 0;

$stmt = $conn->query("SELECT COUNT(*) as total FROM submissions WHERE status = 'Pending'");
$stats['pending_submissions'] = $stmt->fetch_assoc()['total'] ?? 0;

// Training effectiveness
$stats['completion_rate'] = 89;
$stats['avg_effectiveness'] = 4.2;
$stats['active_trainings'] = 24;

// Recent activities
$recent_users = [];
$stmt = $conn->query("SELECT fullname, email, role, created_at FROM users WHERE role != 'admin' ORDER BY created_at DESC LIMIT 5");
while ($row = $stmt->fetch_assoc()) {
    $recent_users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Super Admin Dashboard - Training Management System</title>
    
    <!-- Advanced CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --sidebar-width: 280px;
            --navbar-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Animated Background Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Super Navbar */
        .super-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(44,62,80,0.9) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand-super {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .navbar-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .control-btn {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 10px 15px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
        }

        /* Super Sidebar */
        .super-sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, rgba(44,62,80,0.9) 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            overflow-y: auto;
            z-index: 999;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid var(--glass-border);
        }

        .admin-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .menu-item:hover::before {
            left: 100%;
        }

        .menu-item:hover {
            background: var(--glass-bg);
            color: white;
            border-left-color: #4ecdc4;
            transform: translateX(10px);
        }

        .menu-item.active {
            background: var(--glass-bg);
            border-left-color: #4ecdc4;
            color: white;
        }

        .menu-item i {
            width: 25px;
            margin-right: 15px;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 40px;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* Super Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 50px;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
            margin-bottom: 20px;
        }

        .welcome-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
        }

        /* Super Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 25px 25px 0 0;
        }

        .stat-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        .stat-card:nth-child(1)::before { background: var(--primary-gradient); }
        .stat-card:nth-child(2)::before { background: var(--secondary-gradient); }
        .stat-card:nth-child(3)::before { background: var(--success-gradient); }
        .stat-card:nth-child(4)::before { background: var(--warning-gradient); }

        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            color: white;
            margin-bottom: 25px;
            position: relative;
        }

        .stat-card:nth-child(1) .stat-icon { background: var(--primary-gradient); }
        .stat-card:nth-child(2) .stat-icon { background: var(--secondary-gradient); }
        .stat-card:nth-child(3) .stat-icon { background: var(--success-gradient); }
        .stat-card:nth-child(4) .stat-icon { background: var(--warning-gradient); }

        .stat-value {
            font-size: 3rem;
            font-weight: 900;
            color: white;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .stat-change {
            font-size: 14px;
            color: #4ecdc4;
            font-weight: 600;
        }

        /* Advanced Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            position: relative;
            overflow: hidden;
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .chart-title i {
            margin-right: 15px;
            color: #4ecdc4;
        }

        /* Recent Activity */
        .activity-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #4ecdc4;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(10px);
        }

        .activity-user {
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
        }

        .activity-details {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-btn {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:hover {
            transform: translateY(-5px);
            color: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .action-icon {
            font-size: 30px;
            margin-bottom: 15px;
            color: #4ecdc4;
        }

        .action-label {
            font-weight: 600;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .super-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .super-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .welcome-title {
                font-size: 2.5rem;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .fade-in:nth-child(1) { animation-delay: 0.1s; }
        .fade-in:nth-child(2) { animation-delay: 0.2s; }
        .fade-in:nth-child(3) { animation-delay: 0.3s; }
        .fade-in:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #44a08d, #4ecdc4);
        }
    </style>
</head>
<body>

    <!-- Animated Background Particles -->
    <div class="particles" id="particles"></div>

    <!-- Super Navbar -->
    <nav class="super-navbar">
        <div class="navbar-brand-super">
            <i class="fas fa-crown me-3"></i>
            SUPER ADMIN CONTROL CENTER
        </div>
        <div class="navbar-controls">
            <a href="#" class="control-btn" id="darkModeToggle">
                <i class="fas fa-moon"></i>
            </a>
            <a href="#" class="control-btn" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i>
            </a>
            <a href="../auth/logout.php" class="control-btn">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </nav>

    <!-- Super Sidebar -->
    <div class="super-sidebar">
        <div class="sidebar-header">
            <div class="admin-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 style="color: white; font-weight: 600;">Super Admin</h4>
            <p style="color: rgba(255,255,255,0.7); font-size: 14px;">Ultimate Control</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i>
                User Management
            </a>
            <a href="tasks.php" class="menu-item">
                <i class="fas fa-tasks"></i>
                Task Control
            </a>
            <a href="submissions.php" class="menu-item">
                <i class="fas fa-file-alt"></i>
                Submissions
            </a>
            <a href="analytics.php" class="menu-item">
                <i class="fas fa-chart-line"></i>
                Analytics
            </a>
            <a href="settings.php" class="menu-item">
                <i class="fas fa-cogs"></i>
                System Settings
            </a>
            <a href="logs.php" class="menu-item">
                <i class="fas fa-history"></i>
                Activity Logs
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section fade-in">
            <div class="welcome-content">
                <h1 class="welcome-title">
                    <i class="fas fa-rocket me-3"></i>
                    SUPER ADMIN DASHBOARD
                </h1>
                <p class="welcome-subtitle">
                    Ultimate control center for Training Management System
                </p>
                <div class="mt-4">
                    <span class="badge bg-success me-2">System Online</span>
                    <span class="badge bg-info me-2">All Services Running</span>
                    <span class="badge bg-warning">High Performance</span>
                </div>
            </div>
        </div>

        <!-- Super Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" data-count="<?php echo $stats['users_count']; ?>">0</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-change">+<?php echo $stats['new_users_month']; ?> this month</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-value" data-count="<?php echo $stats['active_trainings']; ?>">0</div>
                <div class="stat-label">Active Trainings</div>
                <div class="stat-change">+3 vs last month</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value" data-count="<?php echo $stats['completion_rate']; ?>">0</div>
                <div class="stat-label">Completion Rate (%)</div>
                <div class="stat-change">+5% improvement</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value" data-count="<?php echo $stats['avg_effectiveness']; ?>">0</div>
                <div class="stat-label">Avg. Effectiveness</div>
                <div class="stat-change">+0.3 vs last month</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions fade-in">
            <a href="users.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="action-label">Add User</div>
            </a>
            <a href="tasks.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-label">Create Task</div>
            </a>
            <a href="analytics.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="action-label">Export Report</div>
            </a>
            <a href="settings.php" class="action-btn">
                <div class="action-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="action-label">System Config</div>
            </a>
        </div>

        <!-- Charts and Activity -->
        <div class="charts-section fade-in">
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-area"></i>
                    Performance Analytics
                </h3>
                <div style="height: 300px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.7);">
                    <div class="text-center">
                        <i class="fas fa-chart-line" style="font-size: 60px; margin-bottom: 20px; color: #4ecdc4;"></i>
                        <p>Advanced analytics dashboard coming soon...</p>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 style="width: 75%; background: linear-gradient(90deg, #4ecdc4, #44a08d);"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-clock"></i>
                    Recent Activity
                </h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($recent_users as $user): ?>
                    <div class="activity-item">
                        <div class="activity-user">
                            <i class="fas fa-user-plus me-2"></i>
                            <?php echo htmlspecialchars($user['fullname']); ?>
                        </div>
                        <div class="activity-details">
                            <?php echo htmlspecialchars($user['email']); ?> • <?php echo ucwords(str_replace('_', ' ', $user['role'])); ?>
                        </div>
                        <div class="activity-time">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('M j, Y g:i A', strtotime($user['created_at'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Create animated particles
        function createParticles() {
            const particles = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particles.appendChild(particle);
            }
        }

        // Animated counter
        function animateCounters() {
            document.querySelectorAll('.stat-value').forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-count'));
                let count = 0;
                const increment = target / 60;
                
                const updateCounter = () => {
                    if (count < target) {
                        count += increment;
                        counter.textContent = target % 1 === 0 ? Math.floor(count) : count.toFixed(1);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
            });
        }

        // Fullscreen toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Dark mode toggle (enhanced)
        document.getElementById('darkModeToggle').addEventListener('click', function(e) {
            e.preventDefault();
            document.body.style.filter = document.body.style.filter === 'invert(1)' ? '' : 'invert(1)';
            this.innerHTML = document.body.style.filter === 'invert(1)' ? 
                '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });

        // Enhanced menu interactions
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            const dateString = now.toLocaleDateString();
            
            // You can add a clock element if needed
            console.log(`${dateString} ${timeString}`);
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            setTimeout(animateCounters, 500);
            setInterval(updateClock, 1000);
            
            // Add loading animation
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Advanced hover effects
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02) rotateX(5deg)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1) rotateX(0deg)';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                toggleFullscreen();
            }
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                document.getElementById('darkModeToggle').click();
            }
        });
    </script>
</body>
</html>