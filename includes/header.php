<!DOCTYPE html>
<html lang="en">

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
            <a href="./auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

<!-- Styles -->
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

    @media screen and (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
    }
</style>
</body>
</html>