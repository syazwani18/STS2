<?php
session_start();
require_once '../config/config.php';
require_once '../config/functions.php';

// Check if user is admin
if (!isAdmin()) {
    header("Location: ../staff/dashboard.php");
    exit();
}

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $user_id = (int)$_POST['user_id'];
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                break;
                
            case 'update_role':
                $user_id = (int)$_POST['user_id'];
                $new_role = $_POST['new_role'];
                $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ? AND role != 'admin'");
                $stmt->bind_param("si", $new_role, $user_id);
                $stmt->execute();
                break;
        }
    }
}

// Get all users
$stmt = $conn->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC");
$users = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👥 User Management - Super Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
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

        /* Include the same navbar and sidebar styles from dashboard */
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

        .users-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            overflow: hidden;
        }

        .table {
            color: white;
            background: transparent;
        }

        .table thead th {
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 20px 15px;
        }

        .table tbody td {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 15px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(255,255,255,0.1);
        }

        .btn-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger-glass {
            background: linear-gradient(135deg, rgba(220,53,69,0.3) 0%, rgba(220,53,69,0.1) 100%);
            border: 1px solid rgba(220,53,69,0.5);
            color: #ff6b6b;
        }

        .btn-danger-glass:hover {
            background: linear-gradient(135deg, rgba(220,53,69,0.5) 0%, rgba(220,53,69,0.3) 100%);
            color: white;
        }

        .role-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .role-staff { background: linear-gradient(135deg, #4ecdc4, #44a08d); }
        .role-head { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .role-admin { background: linear-gradient(135deg, #667eea, #764ba2); }

        .search-container {
            margin-bottom: 25px;
            position: relative;
        }

        .search-input {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 25px;
            padding: 15px 50px 15px 20px;
            width: 100%;
            backdrop-filter: blur(10px);
        }

        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-mini {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            color: white;
        }

        .stat-mini-value {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 5px;
        }

        .stat-mini-label {
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <!-- Super Navbar -->
    <nav class="super-navbar">
        <div class="navbar-brand-super">
            <i class="fas fa-users me-3"></i>
            USER MANAGEMENT CENTER
        </div>
        <div>
            <a href="dashboard.php" class="btn btn-glass me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </nav>

    <!-- Super Sidebar -->
    <div class="super-sidebar">
        <!-- Same sidebar content as dashboard -->
        <div style="padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 30px; color: white;">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 style="color: white; font-weight: 600;">Super Admin</h4>
        </div>
        
        <div style="padding: 20px 0;">
            <a href="dashboard.php" style="display: flex; align-items: center; padding: 15px 25px; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-tachometer-alt" style="width: 25px; margin-right: 15px;"></i>
                Dashboard
            </a>
            <a href="users.php" style="display: flex; align-items: center; padding: 15px 25px; color: white; text-decoration: none; background: rgba(255,255,255,0.1); border-left: 4px solid #4ecdc4;">
                <i class="fas fa-users" style="width: 25px; margin-right: 15px;"></i>
                User Management
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users-cog me-3"></i>
                USER MANAGEMENT
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 1.2rem; margin: 0;">
                Complete control over user accounts and permissions
            </p>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-mini">
                <div class="stat-mini-value"><?php echo count($users); ?></div>
                <div class="stat-mini-label">Total Users</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value"><?php echo count(array_filter($users, fn($u) => $u['role'] === 'staff')); ?></div>
                <div class="stat-mini-label">Staff Members</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value"><?php echo count(array_filter($users, fn($u) => strpos($u['role'], 'head') !== false)); ?></div>
                <div class="stat-mini-label">Department Heads</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-value"><?php echo count(array_filter($users, fn($u) => strtotime($u['created_at']) > strtotime('-30 days'))); ?></div>
                <div class="stat-mini-label">New This Month</div>
            </div>
        </div>

        <!-- Users Container -->
        <div class="users-container">
            <!-- Search -->
            <div class="search-container">
                <input type="text" class="search-input" id="userSearch" placeholder="Search users by name, email, or role...">
                <i class="fas fa-search search-icon"></i>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table" id="usersTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-user me-2"></i>Full Name</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                            <th><i class="fas fa-phone me-2"></i>Phone</th>
                            <th><i class="fas fa-user-tag me-2"></i>Role</th>
                            <th><i class="fas fa-calendar me-2"></i>Joined</th>
                            <th><i class="fas fa-cogs me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><strong>#<?php echo $user['id']; ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo strpos($user['role'], 'head') !== false ? 'head' : 'staff'; ?>">
                                    <?php echo ucwords(str_replace('_', ' ', $user['role'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-glass btn-sm" onclick="editUser(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger-glass btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['fullname']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User Role</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_role">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">Select New Role</label>
                            <select class="form-select" name="new_role" id="editUserRole" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                                <option value="staff">Staff</option>
                                <option value="head_of_division">Head of Division</option>
                                <option value="head_of_department">Head of Department</option>
                                <option value="training_section">Training Section</option>
                                <option value="pengerusi_besar">Pengerusi Besar</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-glass">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#usersTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": false, // We'll use custom search
                "dom": 'rtip',
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
        });

        // Custom search functionality
        document.getElementById('userSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            }
        });

        // Edit user function
        function editUser(userId) {
            document.getElementById('editUserId').value = userId;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        // Delete user function
        function deleteUser(userId, userName) {
            if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="${userId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Add some visual effects
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
                this.style.transition = 'all 0.3s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>