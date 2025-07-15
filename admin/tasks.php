<?php
session_start();
require_once '../config/config.php';
require_once '../config/functions.php';

// Check if user is admin
if (!isAdmin()) {
    header("Location: ../staff/dashboard.php");
    exit();
}

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task'])) {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    
    $stmt = $conn->prepare("INSERT INTO tasks (task_name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $task_name, $description);
    $stmt->execute();
    
    header("Location: tasks.php");
    exit();
}

// Get all tasks
$stmt = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Task Management - Super Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
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

        .page-header {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
            margin-bottom: 15px;
        }

        .task-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .create-task-panel {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            height: fit-content;
        }

        .tasks-list-panel {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
        }

        .panel-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .panel-title i {
            margin-right: 15px;
            color: #4ecdc4;
        }

        .form-control-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 15px;
            padding: 15px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .form-control-glass:focus {
            background: rgba(255,255,255,0.15);
            border-color: #4ecdc4;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
        }

        .form-control-glass::placeholder {
            color: rgba(255,255,255,0.7);
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
        }

        .btn-glass:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary-glass {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            border: none;
            color: white;
        }

        .btn-primary-glass:hover {
            background: linear-gradient(135deg, #44a08d, #4ecdc4);
            color: white;
        }

        .task-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            border-radius: 20px 20px 0 0;
        }

        .task-card:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .task-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 10px;
        }

        .task-description {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: rgba(255,255,255,0.6);
        }

        .task-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm-glass {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.6);
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.3);
        }

        .stats-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-mini-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .stat-mini-value {
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .stat-mini-label {
            font-size: 12px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <!-- Super Navbar -->
    <nav class="super-navbar">
        <div class="navbar-brand-super">
            <i class="fas fa-tasks me-3"></i>
            TASK MANAGEMENT CENTER
        </div>
        <div>
            <a href="dashboard.php" class="btn btn-glass me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </nav>

    <!-- Super Sidebar (same as other pages) -->
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
            <a href="tasks.php" style="display: flex; align-items: center; padding: 15px 25px; color: white; background: rgba(255,255,255,0.1); border-left: 4px solid #4ecdc4; text-decoration: none;">
                <i class="fas fa-tasks" style="width: 25px; margin-right: 15px;"></i>
                Task Management
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list me-3"></i>
                TASK MANAGEMENT
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 1.2rem; margin: 0;">
                Create and manage training tasks and assignments
            </p>
        </div>

        <!-- Mini Stats -->
        <div class="stats-mini">
            <div class="stat-mini-card">
                <div class="stat-mini-value"><?php echo count($tasks); ?></div>
                <div class="stat-mini-label">Total Tasks</div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-value">3</div>
                <div class="stat-mini-label">Active Forms</div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-value">89%</div>
                <div class="stat-mini-label">Completion Rate</div>
            </div>
        </div>

        <!-- Task Grid -->
        <div class="task-grid">
            <!-- Create Task Panel -->
            <div class="create-task-panel">
                <h3 class="panel-title">
                    <i class="fas fa-plus-circle"></i>
                    Create New Task
                </h3>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label text-white mb-2">Task Name</label>
                        <input type="text" class="form-control form-control-glass" name="task_name" placeholder="Enter task name..." required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-white mb-2">Description</label>
                        <textarea class="form-control form-control-glass" name="description" rows="4" placeholder="Enter task description..." required></textarea>
                    </div>
                    
                    <button type="submit" name="create_task" class="btn btn-primary-glass w-100">
                        <i class="fas fa-plus me-2"></i>
                        Create Task
                    </button>
                </form>

                <!-- Quick Task Templates -->
                <div class="mt-4">
                    <h5 class="text-white mb-3">Quick Templates</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-glass btn-sm" onclick="fillTemplate('BPL', 'Borang Permohonan Latihan - Training application form')">
                            <i class="fas fa-file-alt me-2"></i>BPL Form
                        </button>
                        <button class="btn btn-glass btn-sm" onclick="fillTemplate('PKK', 'Penilaian Keberkesanan Kursus - Course effectiveness evaluation')">
                            <i class="fas fa-star me-2"></i>PKK Form
                        </button>
                        <button class="btn btn-glass btn-sm" onclick="fillTemplate('TEA', 'Training Effectiveness Assessment - Post-training evaluation')">
                            <i class="fas fa-chart-line me-2"></i>TEA Form
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tasks List Panel -->
            <div class="tasks-list-panel">
                <h3 class="panel-title">
                    <i class="fas fa-list"></i>
                    Existing Tasks
                </h3>

                <?php if (empty($tasks)): ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard"></i>
                    <h5>No Tasks Created Yet</h5>
                    <p>Create your first task using the form on the left</p>
                </div>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                    <div class="task-card">
                        <div class="task-title"><?php echo htmlspecialchars($task['task_name']); ?></div>
                        <div class="task-description"><?php echo htmlspecialchars($task['description']); ?></div>
                        <div class="task-meta">
                            <span>
                                <i class="fas fa-calendar me-1"></i>
                                Created: <?php echo date('M j, Y', strtotime($task['created_at'])); ?>
                            </span>
                            <div class="task-actions">
                                <button class="btn btn-glass btn-sm-glass" onclick="editTask(<?php echo $task['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-glass btn-sm-glass" onclick="deleteTask(<?php echo $task['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fill template function
        function fillTemplate(name, description) {
            document.querySelector('input[name="task_name"]').value = name;
            document.querySelector('textarea[name="description"]').value = description;
        }

        // Edit task function
        function editTask(taskId) {
            // Implementation for editing task
            alert('Edit functionality will be implemented');
        }

        // Delete task function
        function deleteTask(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                // Implementation for deleting task
                alert('Delete functionality will be implemented');
            }
        }

        // Add hover effects
        document.querySelectorAll('.task-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const taskName = document.querySelector('input[name="task_name"]').value.trim();
            const description = document.querySelector('textarea[name="description"]').value.trim();
            
            if (!taskName || !description) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    </script>
</body>
</html>