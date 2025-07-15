<?php
require_once "../eggeaster/easter.php";
?>

<!-- Modern Navbar Component -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #800000 0%, #a31515 100%); box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="/staff/dashboard.php" style="font-size: 1.4rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
            <i class="fas fa-graduation-cap me-2"></i>
            TRAINING MANAGEMENT SYSTEM
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 mx-1" href="/staff/dashboard.php" style="transition: all 0.3s ease;">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 mx-1" href="/staff/profile.php" style="transition: all 0.3s ease;">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 mx-1" href="https://www.sedco.com.my" target="_blank" style="transition: all 0.3s ease;">
                        <i class="fas fa-globe me-1"></i> Sedco
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 mx-1" href="../auth/logout.php" style="transition: all 0.3s ease; background-color: rgba(255,255,255,0.1);">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modern Sidebar Component -->
<div class="sidebar" style="
    position: fixed;
    top: 70px;
    left: 0;
    width: 280px;
    height: calc(100vh - 70px);
    background: linear-gradient(180deg, #800000 0%, #600000 100%);
    box-shadow: 4px 0 20px rgba(0,0,0,0.1);
    overflow-y: auto;
    z-index: 1020;
">
    <div class="sidebar-header" style="padding: 25px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <h4 style="color: white; font-weight: 600; margin: 0; font-size: 1.2rem;">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </h4>
    </div>
    
    <div class="sidebar-menu" style="padding: 20px 0;">
        <a href="/staff/dashboard.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-home" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Dashboard
        </a>
        
        <a href="/staff/profile.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-user" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Profile
        </a>
        
        <a href="/staff/task.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-tasks" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Tasks
        </a>
        
        <a href="/staff/application.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-file-alt" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Application Status
        </a>
        
        <a href="/staff/task.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-upload" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Submissions
        </a>
        
        <a href="https://www.sedco.com.my" target="_blank" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'; this.style.borderLeftColor='#ffc107'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-globe" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Website Sedco
        </a>
        
        <div style="height: 1px; background: rgba(255,255,255,0.1); margin: 20px 25px;"></div>
        
        <a href="../auth/logout.php" style="
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        " onmouseover="this.style.background='rgba(255,0,0,0.2)'; this.style.color='white'; this.style.borderLeftColor='#ff4757'; this.style.transform='translateX(5px)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.9)'; this.style.borderLeftColor='transparent'; this.style.transform='translateX(0)';">
            <i class="fas fa-sign-out-alt" style="width: 20px; margin-right: 15px; text-align: center; font-size: 16px;"></i>
            Logout
        </a>
    </div>
</div>

<style>
/* Navbar hover effects */
.navbar .nav-link:hover {
    background-color: rgba(255,255,255,0.2) !important;
    color: white !important;
    transform: translateY(-2px);
}

/* Responsive sidebar */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
}
</style>