<?php
session_start();
require_once '../config/config.php';
require_once '../config/functions.php'; 

// Check if user is admin
if (!isAdmin()) {
    header("Location: staff/dashboard.php");
    exit();
}

// Get users count
$stmt = $conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
$users_count = $stmt->fetch_assoc()['total'];

// Get tasks count
$stmt = $conn->query("SELECT COUNT(*) as total FROM tasks");
$tasks_count = $stmt->fetch_assoc()['total'];

// Get submissions count
$stmt = $conn->query("SELECT COUNT(*) as total FROM submissions");
$submissions_count = $stmt->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Training Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Add your existing styles here */
        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card {
            background: linear-gradient(45deg, #B30E02, #800000);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-number"><?php echo $users_count; ?></div>
                    <div>Total Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-tasks"></i>
                    <div class="stat-number"><?php echo $tasks_count; ?></div>
                    <div>Total Tasks</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <i class="fas fa-file-alt"></i>
                    <div class="stat-number"><?php echo $submissions_count; ?></div>
                    <div>Total Submissions</div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h4>Recent Users</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT fullname, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
                            while ($user = $stmt->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$user['fullname']}</td>
                                    <td>{$user['role']}</td>
                                    <td>" . date('Y-m-d', strtotime($user['created_at'])) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h4>Recent Submissions</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>User</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT s.title, u.fullname, s.status FROM submissions s JOIN users u ON s.user_id = u.id ORDER BY s.submitted_at DESC LIMIT 5");
                            while ($submission = $stmt->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$submission['title']}</td>
                                    <td>{$submission['fullname']}</td>
                                    <td>{$submission['status']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>