<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Training Management System</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            background: url('./STS/v2/assets/img/sedco.jpg') no-repeat center center/cover;
            min-height: 100vh; 
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #800000;
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
            background-color: #800000;
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

        .card-container {
            margin-left: 270px;
            margin-top: 100px;
            padding: 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .card {
            background-color: white;
            color: #333;
            border-radius: 12px;
            width: 300px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .card h5 {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .card button {
            background-color: #ffc107;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .card button:hover {
            background-color: #e0a800;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .card-container {
                margin-left: 0;
                margin-top: 140px;
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
                <li class="nav-item"><a class="nav-link" href="dashboard.php">DASHBOARD</a></li>
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
        <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="#"><i class="fas fa-user"></i> Profile</a>
        <a href="#"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#"><i class="fas fa-file-alt"></i> Application status</a>
        <a href="#"><i class="fas fa-file-alt"></i> Submissions</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Cards -->
    <div class="card-container">
        <div class="card">
            <h5>PERMOHONAN LATIHAN (BPL)</h5>
            <a href="bpl.php" class="btn btn-warning">APPLY</a>
        </div>
        <div class="card">
            <h5>PENILAIAN KEBERKESANAN KURSUS</h5>
            <a href="pkk.php" class="btn btn-warning">APPLY</a>
        </div>
        <div class="card">
            <h5>TRAINING EFFECTIVENESS ASSESSMENT FORM</h5>
            <a href="tea.php" class="btn btn-warning">APPLY</a>
        </div>
    </div>

</body>
</html>
   