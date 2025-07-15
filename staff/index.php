<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <title>Dashboard - Training Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { transition: all 0.3s ease-in-out; }
        body {
    background-image: url('.x./assets/img/sedco.jpg'); /* Replace with the path to your image */
    background-size: cover; /* Ensures the image covers the entire background */
    background-position: center center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents the image from repeating */
    transition: all 0.3s ease-in-out;
}

        body.dark-mode { background-color: #1e1e1e; color: white; }
        .dark-mode .navbar, .dark-mode .sidebar { background-color: #333; }
        .dark-mode .card { background-color: #444; color: white; }

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
            background-color: #800000;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content {
            margin-left: 260px; margin-top: 80px; padding: 20px;
            display: flex; justify-content: space-between;
        }

        .tasks-container { width: 70%; }


        .calendar-container {
    width: 100%;
    max-width: 300px; /* Prevents the calendar from exceeding its container */
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    border: 2px solid #B30E02;
    overflow: hidden; /* Prevents content from overflowing */
    
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px;
    background-color: #B30E02;
    color: white;
    padding: 8px;
    border-radius: 5px;
    border: 2px solid #8f0b01;
}

.calendar-header span {
    cursor: pointer;
    font-size: 20px;
    padding: 5px 10px;
    border-radius: 5px;
    transition: 0.3s;
}

.calendar-header span:hover {
    background-color: #8f0b01;
}

.calendar-table {
    width: 100%;
    font-size: 14px;
    border-collapse: collapse;
    border: 2px solid #ddd;
    table-layout: fixed; /* Ensures table stays within the container */
}

.calendar-table th, .calendar-table td {
    padding: 8px;
    text-align: center;
    cursor: pointer;
    border: 1px solid #B30E02;
    width: 14.28%; /* Ensures even distribution within the table */
    height: 40px; /* Sets a fixed height for consistency */
    overflow: hidden; /* Ensures text doesn't overflow */
}

.calendar-table th {
    background-color: #B30E02;
    color: white;
}

.calendar-table td:hover {
    background-color: rgba(179, 14, 2, 0.2);
}

.today {
    background-color: #8f0b01 !important;
    color: white !important;
    font-weight: bold;
    border: 2px solid #8f0b01;
}

.selected-date {
    background-color: #B30E02 !important;
    color: white !important;
    border: 2px solid #8f0b01;
}


    </style>
</head>
<body>

<?php include '../staff/navbar.php'; ?>


    <!-- Main Content -->
    <div class="content">
        
        <!-- Task Section -->
        <div class="tasks-container">
            <h3 class="welcome-message">Welcome, 
                <?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Guest'; ?>!
            </h3>

            <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search tasks..." onkeyup="filterTasks()">

            <div id="taskList">
                <div class="card p-3 mb-3 task-card">
                    <strong>Monday, 17 March 2025</strong>
                    <div class="text-danger">Submission of Log Book <span class="badge bg-danger">Overdue</span></div>
                    <p>BORANG PENILAIAN KEBERKESANAN KURSUS IS DUE</p>
                </div>

                <div class="card p-3 mb-3 task-card">
                    <strong>Thursday, 20 March 2025</strong>
                    <div class="text-primary">Submission of LI Report</div>
                    <p>KD44212 LATIHAN INDUSTRI [1-2024/2025]: Assignment is due</p>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container">
            <div class="calendar-header" id="calendar-header"></div>
            <table class="calendar-table" id="calendar"></table>
            <div id="selected-date" style="margin-top: 10px; font-weight: bold;"></div>
            <button class="btn btn-outline-danger mt-2" onclick="resetCalendar()">Reset Calendar</button>

        </div>

    </div>

    <script>
    let currentDate = new Date();

    function changeMonth(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        generateCalendar();
    }

    function generateCalendar() {
        let month = currentDate.toLocaleString('default', { month: 'long' });
        let year = currentDate.getFullYear();
        let today = new Date();
        let firstDay = new Date(year, currentDate.getMonth(), 1).getDay();
        let lastDate = new Date(year, currentDate.getMonth() + 1, 0).getDate();
        
        document.getElementById("calendar-header").innerHTML = `
            <span onclick="changeMonth(-1)">&#10094;</span> 
            <span>${month} ${year}</span> 
            <span onclick="changeMonth(1)">&#10095;</span>
        `;

        let calendarHTML = "<tr>";
        let weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        calendarHTML += weekdays.map(day => `<th>${day}</th>`).join("") + "</tr><tr>";

        for (let i = 0; i < firstDay; i++) {
            calendarHTML += "<td></td>";
        }

        for (let date = 1; date <= lastDate; date++) {
            if ((firstDay + date - 1) % 7 === 0) calendarHTML += "</tr><tr>";
            let isToday = (date === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) ? "today" : "";
            calendarHTML += `<td class="${isToday}" onclick="selectDate(this, ${date})">${date}</td>`;
        }

        calendarHTML += "</tr>";
        document.getElementById("calendar").innerHTML = calendarHTML;
    }

    function selectDate(element, date) {
        document.querySelectorAll(".calendar-table td").forEach(td => td.classList.remove("selected-date"));
        element.classList.add("selected-date");
        document.getElementById("selected-date").innerText = `Selected Date: ${date} ${document.getElementById("calendar-header").querySelector("span:nth-child(2)").innerText}`;
    }

    function resetCalendar() {
        currentDate = new Date(); // Reset to current date
        generateCalendar(); // Refresh the calendar
        document.getElementById("selected-date").innerText = ""; // Clear selected date display
    }

    generateCalendar();

    // 🔍 Filter Tasks Function
    function filterTasks() {
        const input = document.getElementById("searchBar").value.toLowerCase();
        const taskCards = document.querySelectorAll("#taskList .task-card");

        taskCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(input) ? "" : "none";
        });
    }
</script>
</body>
</html>
