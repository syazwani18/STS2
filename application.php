<?php
session_start();

// Example user and store data (replace with your real database queries)
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
    <title>Store Application - Training Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            background: url('background.jpg') no-repeat center center/cover;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #B30E02;
            padding: 15px 30px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
        }

        .navbar .nav-link:hover {
            text-decoration: underline;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 65px;
            left: 0;
            background-color: #B30E02;
            padding-top: 20px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: flex;
            align-items: center;
            transition: background 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #a31515;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content {
            margin-left: 270px;
            margin-top: 90px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: #fff;
        }

        .card h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #eeeeee;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .btn-custom {
            background-color: #B30E02;
            color: white;
            border-radius: 25px;
            padding: 5px 15px;
            font-size: 14px;
        }

        .btn-custom:hover {
            background-color: #9e0c00;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
                padding: 20px;
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
    <a href="#"><i class="fas fa-home"></i> Home</a>
    <a href="#"><i class="fas fa-user"></i> Profile</a>
    <a href="#"><i class="fas fa-tasks"></i> Tasks</a>
    <a href="#"><i class="fas fa-file-alt"></i> Submissions</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="card p-4">
        <h2 class="text-center">Status Application</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mb-3">
            <?php if ($user['role'] == 2): ?>
                <a href="store_create.php" class="btn btn-custom me-2">Create New Store</a>
            <?php endif; ?>
            <button onclick="window.history.back()" class="btn btn-outline-secondary rounded-pill">Back</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Block</th>
                        <th>Store Name</th>
                        <th>Floor</th>
                        <th>Matric Number</th>
                        <th>Phone Number</th>
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
                        <td><?= htmlspecialchars($store['store']['store_name']) ?></td>
                        <td><?= htmlspecialchars($store['store']['floor']) ?></td>
                        <td><?= htmlspecialchars($store['user']['matric_number']) ?></td>
                        <td><?= htmlspecialchars($store['user']['phone_number']) ?></td>
                        <td>
                            <span class="badge <?= $store['status'] == 'Approved' ? 'bg-success' : ($store['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                <?= htmlspecialchars($store['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['role'] == 2 && $store['status'] == 'Pending'): ?>
                                <form action="store_approve.php?id=<?= $store['id'] ?>" method="POST" style="display:inline;">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill">Approve</button>
                                </form>
                                <form action="store_reject.php?id=<?= $store['id'] ?>" method="POST" style="display:inline;">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill">Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="store_show.php?id=<?= $store['store_id'] ?>" class="btn btn-secondary btn-sm rounded-pill">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            <nav>
                <ul class="pagination pagination-rounded mb-0">
                    <li class="page-item disabled"><a class="page-link">Prev</a></li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">Next</a></li>
                </ul>
            </nav>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
