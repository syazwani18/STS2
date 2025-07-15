<?php
session_start();

$user = [
    'role' => 2,
    'matric_number' => 'A123456',
    'phone_number' => '0123456789'
];

$stores = [
    [
        'id' => 1,
        'store_id' => 101,
        'store' => ['block' => 'A', 'store_name' => 'Stationery Corner', 'floor' => 1],
        'user' => ['matric_number' => 'A123456', 'phone_number' => '0123456789'],
        'status' => 'Pending'
    ],
    [
        'id' => 2,
        'store_id' => 102,
        'store' => ['block' => 'B', 'store_name' => 'Snack Bar', 'floor' => 2],
        'user' => ['matric_number' => 'A654321', 'phone_number' => '0198765432'],
        'status' => 'Approved'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interactive Application Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body {
            background: url('background.jpg') no-repeat center center/cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #B30E02;
            padding: 15px 30px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar .nav-link {
            color: white !important;
        }

        .sidebar {
            position: fixed;
            top: 65px;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: rgba(179, 14, 2, 0.95);
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: #B30E02;
            padding-left: 30px;
        }

        .content {
            margin-left: 270px;
            padding: 100px 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        .btn-custom {
            background-color: #B30E02;
            color: white;
            border-radius: 20px;
        }

        .btn-custom:hover {
            background-color: #B30E02;
        }

        .badge {
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 12px;
        }

        #searchInput {
            width: 100%;
            max-width: 300px;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">TRAINING MANAGEMENT SYSTEM</span>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="#">ABOUT US</a></li>
                <li class="nav-item"><a class="nav-link" href="#">CONTACT US</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">LOG OUT</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-white text-center mb-4">Tasks</h4>
    <a href="#"><i class="fas fa-home me-2"></i> Home</a>
    <a href="#"><i class="fas fa-user me-2"></i> Profile</a>
    <a href="#"><i class="fas fa-tasks me-2"></i> Tasks</a>
    <a href="#"><i class="fas fa-file-alt me-2"></i> Application Status</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="card p-4">
        <h3 class="text-center mb-4">Application Status</h3>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by department or status...">
            <?php if ($user['role'] == 2): ?>
                <a href="task.php" class="btn btn-custom">➕ New Application</a>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center" id="statusTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tittle</th>
                        <th>Status</th>
                        <th>Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stores as $index => $store): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($store['store']['block']) ?></td>
                            <td>
                                <span class="badge <?= $store['status'] == 'Approved' ? 'bg-success' : ($store['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                    <?= htmlspecialchars($store['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['role'] == 2 && $store['status'] == 'Pending'): ?>
                                    <form action="store_approve.php?id=<?= $store['id'] ?>" method="POST" class="d-inline">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill" title="Approve"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form action="store_reject.php?id=<?= $store['id'] ?>" method="POST" class="d-inline">
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" title="Reject"><i class="fas fa-times"></i></button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="store_show.php?id=<?= $store['store_id'] ?>" class="btn btn-outline-dark btn-sm rounded-pill" title="View Details"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <nav>
                <ul class="pagination pagination-sm">
                    <li class="page-item disabled"><a class="page-link">Prev</a></li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live search filter
    $('#searchInput').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $('#statusTable tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().includes(value));
        });
    });
</script>
</body>
</html>
