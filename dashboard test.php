<?php
$totalRegistrations = 328;
$activeTrainings = 24;
$completionRate = 89;
$avgEffectiveness = 4.2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Training Management Dashboard</title>
  <style>
    :root {
      --blue: #3b82f6;
      --green: #10b981;
      --purple: #8b5cf6;
      --orange: #f97316;
      --bg-light: #f3f4f6;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: #1f2937;
    }

    h1 {
      font-size: 30px;
      margin-bottom: 5px;
    }

    p.description {
      color: #6b7280;
      margin-bottom: 25px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .filters {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .filters select,
    .filters button {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 14px;
      border: 1px solid #d1d5db;
    }

    .filters button {
      background-color: var(--blue);
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .filters button:hover {
      background-color: #2563eb;
    }

    .cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      flex: 1;
      min-width: 220px;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 10px;
    }

    .card .value {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .card .change {
      font-size: 13px;
      color: green;
    }

    .icon-circle {
      position: absolute;
      top: -20px;
      right: -20px;
      background: #ddd;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      color: white;
      opacity: 0.8;
      z-index: 0;
    }

    .blue-bg { background-color: var(--blue); }
    .green-bg { background-color: var(--green); }
    .purple-bg { background-color: var(--purple); }
    .orange-bg { background-color: var(--orange); }

    .charts {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .chart {
      flex: 1;
      min-width: 300px;
      background: white;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .chart h3 {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .chart img {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }

    @media (max-width: 768px) {
      .top-bar {
        flex-direction: column;
        align-items: flex-start;
      }
      .filters {
        justify-content: flex-start;
        margin-top: 10px;
      }
    }

    /* Sidebar styles */
    .layout {
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 220px;
      background-color: #1f2937;
      color: white;
      padding: 30px 20px;
    }

    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 20px;
    }

    .sidebar nav {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      font-size: 16px;
    }

    .main-content {
      flex: 1;
      padding: 20px;
    }
  </style>
</head>
<body>

<div class="layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>📁 Menu</h2>
    <nav>
      <a href="#">📄 Submission Tasks</a>
      <a href="#">📝 Task</a>
      <a href="#">👨‍💼 Individual Reports</a> 
      <a href="#">👤 Profile</a> 
    </nav>
  </div>

  <!-- Main Dashboard Content -->
  <div class="main-content">
    <div class="top-bar">
      <div>
        <h1>🎓 Training Management Dashboard</h1>
        <p class="description">Monitor and analyze your organization's training performance</p>
      </div>

      <div class="filters">
        <select>
          <option>Monthly</option>
          <option>Quarterly</option>
          <option>Yearly</option>
        </select>

        <select>
          <option>All Departments</option>
          <option>HR</option>
          <option>MIS</option>
          <option>Finance</option>
          <option>Bpu</option>
        </select>

        <button>📥 Export Report</button>
        <button onclick="location.reload();">🔄 Refresh</button>
      </div>
    </div>

    <!-- Statistic Cards -->
    <div class="cards">
      <div class="card">
        <div class="icon-circle blue-bg">👥</div>
        <h3>Total Registrations</h3>
        <div class="value" data-count="<?php echo $totalRegistrations; ?>">0</div>
        <div class="change">+12% vs last month</div>
      </div>
      <div class="card">
        <div class="icon-circle green-bg">📘</div>
        <h3>Active Trainings</h3>
        <div class="value" data-count="<?php echo $activeTrainings; ?>">0</div>
        <div class="change">+3 vs last month</div>
      </div>
      <div class="card">
        <div class="icon-circle purple-bg">🎯</div>
        <h3>Completion Rate</h3>
        <div class="value" data-count="<?php echo $completionRate; ?>">0</div>
        <div class="change">+5% vs last month</div>
      </div>
      <div class="card">
        <div class="icon-circle orange-bg">🏆</div>
        <h3>Avg. Effectiveness</h3>
        <div class="value" data-count="<?php echo $avgEffectiveness; ?>">0</div>
        <div class="change">+0.3 vs last month</div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="charts">
      <div class="chart">
        <h3>📊 Registration Trends</h3>
        <img src="chart-placeholder.png" alt="Line chart">
      </div>
      <div class="chart">
        <h3>📈 Department Distribution</h3>
        <img src="piechart-placeholder.png" alt="Pie chart">
      </div>
    </div>
  </div>
</div>

<!-- Counter Animation Script -->
<script>
  document.querySelectorAll('.value').forEach(counter => {
    const update = () => {
      const target = parseFloat(counter.getAttribute('data-count'));
      let count = parseFloat(counter.innerText);
      const increment = target / 60;
      if (count < target) {
        count += increment;
        counter.innerText = (target % 1 === 0) ? Math.floor(count) : count.toFixed(1);
        setTimeout(update, 20);
      } else {
        counter.innerText = target;
      }
    };
    update();
  });
</script>

</body>
</html>
