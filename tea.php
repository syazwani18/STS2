<!DOCTYPE html>
< lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Effectiveness Form - Tasks</title>

  <!-- Bootstrap CSS and Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
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

    .form-container {
      margin-left: 270px;
      margin-top: 90px;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 95%;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
      vertical-align: middle;
    }

    .no-border {
      border: none !important;
      text-align: left;
    }

    .input-field {
      width: 100%;
      border: none;
      border-bottom: 1px solid black;
      outline: none;
      padding: 2px 4px;
    }

    .center {
      text-align: center;
    }

    .rating-info {
      font-size: 14px;
      color: #333;
    }

    select, input[type="number"], input[type="text"], input[type="date"] {
      padding: 4px;
      border-radius: 4px;
      border: 1px solid #ccc;
      width: 100%;
    }

    input[type="submit"], .btn-secondary {
      padding: 10px 30px;
      font-weight: 600;
    }

    em {
      color: #555;
    }

    hr {
      border: 1px solid #ccc;
    }

    @media print {
      .navbar, .sidebar, .btn {
        display: none;
      }
      .form-container {
        margin: 0;
        box-shadow: none;
        width: 100%;
      }
      @media print {
  * {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }

  body {
    zoom: 80%; /* Scale down the entire content */
    margin: 0;
  }

  .navbar, .sidebar, .btn {
    display: none !important;
  }

  .form-container {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    box-shadow: none;
    border: none;
  }

  table, tr, td, th {
    page-break-inside: avoid !important;
  }

  @page {
    size: A4 portrait;
    margin: 10mm;
  }

  html, body {
    height: auto;
    overflow: visible;
  }
}

    }
  </style>

  <script>
    function printForm() {
      window.print();
    }
  </script>
</head>
<>

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
    <h4 class="text-white text-center mb-4">EA Form</h4>
    <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="#"><i class="fas fa-user"></i> Profile</a>
    <a href="task.php"><i class="fas fa-chart-line"></i> Tasks</a>
    <a href="#"><i class="fas fa-file-alt"></i> Application Status</a>
    <a href="#"><i class="fas fa-file-alt"></i> Submissions</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="form-container">
    <div class="center mb-4">
      <img src=".//STS/v2/assets/img/logo.png" alt="SEDCO Logo" style="height:60px;"><br>
      <h5 class="mt-3 fw-bold">TRAINING EFFECTIVENESS ASSESSMENT FORM</h5>
      <em>Post-Training Evaluation – Improvement Assessment (Conducted in June or December of the Training Year)</em>
    </div>

    <form method="post" action="submit_form.php">
      <table>
        <tr>
          <td class="no-border" colspan="2">Employee Name: <input type="text" name="employee_name" class="input-field" required></td>
          <td class="no-border" colspan="2">Division/Section: <input type="text" name="division" class="input-field" required></td>
        </tr>
        <tr>
          <td class="no-border" colspan="4">
            Evaluation Period:
            <label><input type="radio" name="month" value="June"> January / June</label>
            <label><input type="radio" name="month" value="December" checked> July / December</label>
            <br><small class="text-muted">(Please tick (√) the applicable evaluation period)</small>
          </td>
        </tr>
      </table>

      <div class="center my-3">
        <strong>Rating Scale</strong><br>
        <span class="rating-info">Poor (1) &nbsp; Average (2) &nbsp; Good (3) &nbsp; Excellent (4)</span>
      </div>

      <table>
        <thead>
          <tr>
            <th rowspan="2">Training Title</th>
            <th colspan="5">Assessment Criteria</th>
            <th rowspan="2">Total Score</th>
            <th rowspan="2">Competency Level</th>
            <th rowspan="2">Additional Comments</th>
          </tr>
          <tr>
            <th>Productivity</th>
            <th>Quality of Work</th>
            <th>Skill Enhancement</th>
            <th>Application of Knowledge</th>
            <th>Attitude</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $trainings = [
              "Bengkel Klasifikasi Sistem Fail Fungsian",
              "Public Speaking & Presentation Skill"
            ];
            foreach ($trainings as $index => $training) {
              echo "<tr>
                <td>$training</td>";
              for ($i = 0; $i < 5; $i++) {
                echo "<td><input type='number' name='score_{$index}[]' min='1' max='4' style='width: 50px;'></td>";
              }
              echo "
                <td><input type='text' name='total_score_$index' style='width: 50px;'></td>
                <td>
                  <select name='competency_level_$index'>
                    <option value='Fail'>Fail</option>
                    <option value='Probation'>Probation</option>
                    <option value='Pass'>Pass</option>
                    <option value='Merit'>Merit</option>
                  </select>
                </td>
                <td><input type='text' name='comments_$index'></td>
              </tr>";
            }
          ?>
        </tbody>
      </table>

      <p><strong>Note:</strong> Please select the appropriate competency ranking based on the employee’s performance after training.</p>

      <table>
        <thead>
          <tr><th>Ranking</th><th>Description</th></tr>
        </thead>
        <tbody>
          <tr><td>Fail</td><td>0 – 7 points. No significant improvement observed. Re-training recommended.</td></tr>
          <tr><td>Probation</td><td>8 – 12 points. Requires supervision for 6 months. Re-assessment needed.</td></tr>
          <tr><td>Pass</td><td>13 – 17 points. Can perform tasks with minimal supervision.</td></tr>
          <tr><td>Merit</td><td>18 points. Shows excellent competency. Can guide others.</td></tr>
        </tbody>
      </table>

      <table>
        <tr><td class="no-border">Evaluated by: <strong></strong></td></tr>
        <tr><td class="no-border">Head of Division/Section: <input type="text" name="head_division" class="input-field" required></td></tr>
        <tr><td class="no-border">Date of Evaluation: <input type="date" name="date" class="input-field" required></td></tr>
        <tr><td class="no-border">Signature:<br><canvas id="signatureCanvas" width="300" height="100" style="border:1px solid #ccc; border-radius:5px; background-color:#fff;"></canvas>
        <br><button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearCanvas()">Clear Signature</button>
        <input type="hidden" name="signature" id="signatureInput" required>
      </td>
</tr>

      </table>

      <div class="center mt-4">
        <input type="submit" value="Submit Form" class="btn btn-primary">
        <button type="button" onclick="printForm()" class="btn btn-secondary ms-3">Print Form</button>
      </div>
    </form>
  </div>

  <script>
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    canvas.addEventListener('mousedown', (e) => {
      drawing = true;
      ctx.beginPath();
      ctx.moveTo(e.offsetX, e.offsetY);
    });

    canvas.addEventListener('mousemove', (e) => {
      if (!drawing) return;
      ctx.lineTo(e.offsetX, e.offsetY);
      ctx.strokeStyle = '#000';
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.stroke();
    });

    canvas.addEventListener('mouseup', () => drawing = false);
    canvas.addEventListener('mouseleave', () => drawing = false);

    function clearCanvas() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      document.getElementById('signatureInput').value = '';
    }

    document.querySelector('form').addEventListener('submit', function (e) {
      const dataURL = canvas.toDataURL('image/png');
      document.getElementById('signatureInput').value = dataURL;
    });
  </script>

<script>
  const canvas = document.getElementById('signatureCanvas');
  const ctx = canvas.getContext('2d');
  let drawing = false;

  canvas.addEventListener('mousedown', (e) => {
    drawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
  });

  canvas.addEventListener('mousemove', (e) => {
    if (!drawing) return;
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.stroke();
  });

  canvas.addEventListener('mouseup', () => drawing = false);
  canvas.addEventListener('mouseleave', () => drawing = false);

  function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('signatureInput').value = '';
  }

  // Capture signature on form submit
  document.querySelector('form').addEventListener('submit', function (e) {
    const dataURL = canvas.toDataURL('image/png');
    document.getElementById('signatureInput').value = dataURL;
  });
</script>

</body>
</html>
