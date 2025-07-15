<?php
session_start();
require_once '../config/config.php';
require_once '../config/functions.php';

// Check if user is admin
if (!isAdmin()) {
    header("Location: ../staff/dashboard.php");
    exit();
}

// Get analytics data
$analytics = [];

// User registration trends (last 12 months)
$stmt = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as count
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month
");
$analytics['user_trends'] = $stmt->fetch_all(MYSQLI_ASSOC);

// Role distribution
$stmt = $conn->query("
    SELECT 
        role,
        COUNT(*) as count
    FROM users 
    WHERE role != 'admin'
    GROUP BY role
");
$analytics['role_distribution'] = $stmt->fetch_all(MYSQLI_ASSOC);

// Recent activity
$stmt = $conn->query("
    SELECT 
        fullname,
        email,
        role,
        created_at
    FROM users 
    WHERE role != 'admin'
    ORDER BY created_at DESC 
    LIMIT 10
");
$analytics['recent_activity'] = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 Advanced Analytics - Super Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --sidebar-width: 280px;
            --navbar-height: 80px;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

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

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 40px;
            min-height: calc(100vh - var(--navbar-height));
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .analytics-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            position: relative;
            overflow: hidden;
        }

        .analytics-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            border-radius: 25px 25px 0 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 15px;
            color: #4ecdc4;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .metric-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            color: white;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .metric-label {
            font-size: 14px;
            opacity: 0.8;
            font-weight: 600;
        }

        .metric-change {
            font-size: 12px;
            margin-top: 5px;
            color: #4ecdc4;
        }

        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #4ecdc4;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(10px);
        }

        .export-section {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin: 0 10px;
        }

        .btn-glass:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary-glass {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            border: none;
        }

        .btn-success-glass {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            border: none;
        }

        .btn-info-glass {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
        }

        /* Custom scrollbar */
        .activity-list::-webkit-scrollbar {
            width: 6px;
        }

        .activity-list::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .activity-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Super Navbar -->
    <nav class="super-navbar">
        <div class="navbar-brand-super">
            <i class="fas fa-chart-line me-3"></i>
            ADVANCED ANALYTICS CENTER
        </div>
        <div>
            <a href="dashboard.php" class="btn btn-glass me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </nav>

    <!-- Super Sidebar -->
    <div style="position: fixed; top: 80px; left: 0; width: 280px; height: calc(100vh - 80px); background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, rgba(44,62,80,0.9) 100%); backdrop-filter: blur(20px); border-right: 1px solid rgba(255,255,255,0.2); z-index: 999;">
        <div style="padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 30px; color: white;">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 style="color: white; font-weight: 600;">Super Admin</h4>
        </div>
        
        <div style="padding: 20px 0;">
            <a href="dashboard.php" style="display: flex; align-items: center; padding: 15px 25px; color: rgba(255,255,255,0.8); text-decoration: none;">
                <i class="fas fa-tachometer-alt" style="width: 25px; margin-right: 15px;"></i>
                Dashboard
            </a>
            <a href="users.php" style="display: flex; align-items: center; padding: 15px 25px; color: rgba(255,255,255,0.8); text-decoration: none;">
                <i class="fas fa-users" style="width: 25px; margin-right: 15px;"></i>
                User Management
            </a>
            <a href="analytics.php" style="display: flex; align-items: center; padding: 15px 25px; color: white; background: rgba(255,255,255,0.1); border-left: 4px solid #4ecdc4; text-decoration: none;">
                <i class="fas fa-chart-line" style="width: 25px; margin-right: 15px;"></i>
                Analytics
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div style="background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; padding: 40px; margin-bottom: 30px; text-align: center;">
            <h1 style="font-size: 3rem; font-weight: 900; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); background-size: 200% 200%; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; animation: gradientShift 3s ease infinite; margin-bottom: 15px;">
                <i class="fas fa-chart-area me-3"></i>
                ADVANCED ANALYTICS
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 1.2rem; margin: 0;">
                Deep insights and comprehensive data analysis
            </p>
        </div>

        <!-- Key Metrics -->
        <div class="metric-row">
            <div class="metric-card">
                <div class="metric-value">328</div>
                <div class="metric-label">Total Users</div>
                <div class="metric-change">+12% this month</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">89%</div>
                <div class="metric-label">Completion Rate</div>
                <div class="metric-change">+5% improvement</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">4.2</div>
                <div class="metric-label">Avg Rating</div>
                <div class="metric-change">+0.3 vs last month</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">24</div>
                <div class="metric-label">Active Trainings</div>
                <div class="metric-change">+3 new programs</div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="export-section">
            <h3 style="color: white; margin-bottom: 20px;">
                <i class="fas fa-download me-2"></i>
                Export Reports
            </h3>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 25px;">
                Generate comprehensive reports for different time periods and data types
            </p>
            <div>
                <a href="#" class="btn-glass btn-primary-glass">
                    <i class="fas fa-file-excel me-2"></i>
                    Excel Report
                </a>
                <a href="#" class="btn-glass btn-success-glass">
                    <i class="fas fa-file-pdf me-2"></i>
                    PDF Report
                </a>
                <a href="#" class="btn-glass btn-info-glass">
                    <i class="fas fa-chart-bar me-2"></i>
                    Dashboard Export
                </a>
            </div>
        </div>

        <!-- Analytics Grid -->
        <div class="analytics-grid">
            <!-- User Registration Trends -->
            <div class="analytics-card">
                <h3 class="card-title">
                    <i class="fas fa-user-plus"></i>
                    User Registration Trends
                </h3>
                <div class="chart-container">
                    <canvas id="userTrendsChart"></canvas>
                </div>
            </div>

            <!-- Role Distribution -->
            <div class="analytics-card">
                <h3 class="card-title">
                    <i class="fas fa-users-cog"></i>
                    Role Distribution
                </h3>
                <div class="chart-container">
                    <canvas id="roleDistributionChart"></canvas>
                </div>
            </div>

            <!-- Training Effectiveness -->
            <div class="analytics-card">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Training Effectiveness
                </h3>
                <div class="chart-container">
                    <canvas id="effectivenessChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="analytics-card">
                <h3 class="card-title">
                    <i class="fas fa-clock"></i>
                    Recent Activity
                </h3>
                <div class="activity-list">
                    <?php foreach ($analytics['recent_activity'] as $activity): ?>
                    <div class="activity-item">
                        <div style="font-weight: 600; color: white; margin-bottom: 5px;">
                            <i class="fas fa-user-plus me-2"></i>
                            <?php echo htmlspecialchars($activity['fullname']); ?>
                        </div>
                        <div style="font-size: 14px; color: rgba(255,255,255,0.7); margin-bottom: 5px;">
                            <?php echo htmlspecialchars($activity['email']); ?> • 
                            <?php echo ucwords(str_replace('_', ' ', $activity['role'])); ?>
                        </div>
                        <div style="font-size: 12px; color: rgba(255,255,255,0.5);">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Chart.js configuration
        Chart.defaults.color = 'rgba(255, 255, 255, 0.8)';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

        // User Trends Chart
        const userTrendsCtx = document.getElementById('userTrendsChart').getContext('2d');
        new Chart(userTrendsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'New Users',
                    data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                    borderColor: '#4ecdc4',
                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Role Distribution Chart
        const roleDistributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
        new Chart(roleDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Staff', 'Head of Division', 'Head of Department', 'Training Section'],
                datasets: [{
                    data: [45, 25, 20, 10],
                    backgroundColor: [
                        '#4ecdc4',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Training Effectiveness Chart
        const effectivenessCtx = document.getElementById('effectivenessChart').getContext('2d');
        new Chart(effectivenessCtx, {
            type: 'bar',
            data: {
                labels: ['BPL Forms', 'PKK Evaluations', 'TEA Assessments', 'Training Programs'],
                datasets: [{
                    label: 'Effectiveness Score',
                    data: [4.2, 4.5, 3.8, 4.1],
                    backgroundColor: [
                        'rgba(78, 205, 196, 0.8)',
                        'rgba(240, 147, 251, 0.8)',
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(67, 233, 123, 0.8)'
                    ],
                    borderColor: [
                        '#4ecdc4',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Add animation to metric cards
        document.querySelectorAll('.metric-card').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Export functionality
        document.querySelectorAll('.btn-glass').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const type = this.textContent.trim();
                alert(`${type} export functionality will be implemented`);
            });
        });
    </script>
</body>
</html>