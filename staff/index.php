<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Training Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #800000;
            --primary-hover: #a31515;
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            height: var(--navbar-height);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .navbar .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            border-radius: 20px;
            padding: 8px 16px !important;
        }

        .navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: white !important;
            transform: translateY(-2px);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--navbar-height));
            background: linear-gradient(180deg, var(--primary-color) 0%, #600000 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1020;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h4 {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #ffc107;
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.15);
            border-left-color: #ffc107;
            color: white;
        }

        .sidebar-menu a i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 30px;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card-description {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Task Cards */
        .task-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 15px;
            color: var(--primary-color);
        }

        .task-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            border-left: 5px solid #28a745;
        }

        .task-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .task-card.overdue {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
        }

        .task-date {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .task-title {
            color: #495057;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .task-description {
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
        }

        /* Calendar Styles */
        .calendar-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            color: white;
            border-radius: 15px;
            font-weight: 600;
        }

        .calendar-nav {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .calendar-nav:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th {
            background: #f8f9fa;
            color: #495057;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #dee2e6;
        }

        .calendar-table td {
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .calendar-table td:hover {
            background: rgba(128, 0, 0, 0.1);
        }

        .calendar-table td.today {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .calendar-table td.selected-date {
            background: #ffc107;
            color: #000;
            font-weight: 600;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            margin-bottom: 25px;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px 12px 50px;
            font-size: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.25);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                TRAINING MANAGEMENT SYSTEM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-bell me-1"></i> Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="fas fa-user me-1"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h4>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="profile.php">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="task.php">
                <i class="fas fa-tasks"></i> Tasks
            </a>
            <a href="application.php">
                <i class="fas fa-file-alt"></i> Application Status
            </a>
            <a href="task.php">
                <i class="fas fa-upload"></i> Submissions
            </a>
            <a href="https://www.sedco.com.my" target="_blank">
                <i class="fas fa-globe"></i> Website Sedco
            </a>
            <a href="../auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section fade-in">
            <div class="welcome-content">
                <h1 class="welcome-title">
                    Welcome back, <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Guest'; ?>!
                </h1>
                <p class="welcome-subtitle">Ready to continue your training journey today?</p>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-grid fade-in">
            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="card-title">Active Tasks</h3>
                <p class="card-description">You have 2 pending tasks that require your attention. Stay on track with your training schedule.</p>
            </div>

            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="card-title">Progress</h3>
                <p class="card-description">Track your training progress and see how you're improving over time.</p>
            </div>

            <div class="dashboard-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="card-title">Certifications</h3>
                <p class="card-description">View your completed certifications and upcoming training opportunities.</p>
            </div>
        </div>

        <div class="row">
            <!-- Tasks Section -->
            <div class="col-lg-8">
                <div class="task-section fade-in">
                    <h2 class="section-title">
                        <i class="fas fa-tasks"></i>
                        Recent Tasks
                    </h2>

                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control search-input" id="searchBar" placeholder="Search tasks..." onkeyup="filterTasks()">
                    </div>

                    <div id="taskList">
                        <div class="task-card overdue">
                            <div class="task-date">Monday, 17 March 2025</div>
                            <div class="task-title">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Submission of Log Book 
                                <span class="badge bg-danger ms-2">Overdue</span>
                            </div>
                            <p class="task-description">BORANG PENILAIAN KEBERKESANAN KURSUS IS DUE</p>
                        </div>

                        <div class="task-card">
                            <div class="task-date">Thursday, 20 March 2025</div>
                            <div class="task-title">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                Submission of LI Report
                                <span class="badge bg-warning text-dark ms-2">Pending</span>
                            </div>
                            <p class="task-description">KD44212 LATIHAN INDUSTRI [1-2024/2025]: Assignment is due</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="col-lg-4">
                <div class="calendar-container fade-in">
                    <div class="calendar-header">
                        <button class="calendar-nav" onclick="changeMonth(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="calendar-title"></span>
                        <button class="calendar-nav" onclick="changeMonth(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <table class="calendar-table" id="calendar"></table>
                    <div id="selected-date" class="mt-3 text-center text-muted"></div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-danger btn-sm" onclick="resetCalendar()">
                            <i class="fas fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentDate = new Date();

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar();
        }

        function generateCalendar() {
            const month = currentDate.toLocaleString('default', { month: 'long' });
            const year = currentDate.getFullYear();
            const today = new Date();
            const firstDay = new Date(year, currentDate.getMonth(), 1).getDay();
            const lastDate = new Date(year, currentDate.getMonth() + 1, 0).getDate();
            
            document.getElementById("calendar-title").innerHTML = `${month} ${year}`;

            let calendarHTML = "<tr>";
            const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            calendarHTML += weekdays.map(day => `<th>${day}</th>`).join("") + "</tr><tr>";

            for (let i = 0; i < firstDay; i++) {
                calendarHTML += "<td></td>";
            }

            for (let date = 1; date <= lastDate; date++) {
                if ((firstDay + date - 1) % 7 === 0) calendarHTML += "</tr><tr>";
                const isToday = (date === today.getDate() && 
                               currentDate.getMonth() === today.getMonth() && 
                               currentDate.getFullYear() === today.getFullYear()) ? "today" : "";
                calendarHTML += `<td class="${isToday}" onclick="selectDate(this, ${date})">${date}</td>`;
            }

            calendarHTML += "</tr>";
            document.getElementById("calendar").innerHTML = calendarHTML;
        }

        function selectDate(element, date) {
            document.querySelectorAll(".calendar-table td").forEach(td => td.classList.remove("selected-date"));
            element.classList.add("selected-date");
            const monthYear = document.getElementById("calendar-title").innerText;
            document.getElementById("selected-date").innerText = `Selected: ${date} ${monthYear}`;
        }

        function resetCalendar() {
            currentDate = new Date();
            generateCalendar();
            document.getElementById("selected-date").innerText = "";
        }

        function filterTasks() {
            const input = document.getElementById("searchBar").value.toLowerCase();
            const taskCards = document.querySelectorAll("#taskList .task-card");

            taskCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(input) ? "" : "none";
            });
        }

        // Initialize calendar on page load
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar();
        });

        // Add fade-in animation to elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>