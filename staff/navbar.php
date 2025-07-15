 <?php
  require_once "../eggeaster/easter.php";
  ?>
 
 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">TRAINING MANAGEMENT SYSTEM</span>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                   
                    <li class="nav-item"><a class="nav-link" href="./auth/logout.php">LOG OUT</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white text-center mb-4">Dashboard</h4>
        <a href="/staff/dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="/staff/#"><i class="fas fa-user"></i> Profile</a>
        <a href="/staff/task.php"><i class="fas fa-chart-line"></i> Tasks</a>
        <a href="#"><i class="fas fa-file-alt"></i> Application status</a>
        <a href="/staff/task.php"><i class="fas fa-file-alt"></i> Submissions</a>
        <a href="https://www.sedco.com.my"><i class="fas fa-file-alt"></i>Website Sedco</a>
        <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
