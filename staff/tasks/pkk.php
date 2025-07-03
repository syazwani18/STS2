<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Display the notification message
    echo "<div id='notification' style='
        background-color: #e0ffe0;
        padding: 20px 30px;
        border: 1px solid #28a745;
        border-left: 10px solid #28a745;
        margin: 100px auto 20px auto;
        max-width: 1000px;
        font-weight: bold;
        color: #155724;
        font-size: 18px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-align: center;
    '>✅ Borang telah dihantar dengan jayanya. Terima kasih!</div>";

    // JavaScript to redirect after 3 seconds
    echo "<script>
        setTimeout(function() {
            window.location.href = 'task.php'; // Redirect to task.php after 3 seconds
        }, 3000); // 3000 milliseconds (3 seconds)
    </script>";

    exit();
}
?>


<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Management System - Borang Penilaian</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            background: #f7f7f7;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Notification styling */
        #notification {
            background-color: #e9f9ec;
    padding: 20px 30px;
    border-left: 6px solid #28a745;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: #155724;
    font-weight: 500;
    max-width:1000px;
    width: 100%;
    text-align: left;
    display: none;
    position: fixed;
    top: 50%;
    left: 58%;
    transform: translate(-50%, -50%);
    z-index: 10000;
    font-size: 16px;
    border-radius: 10px;
}
    </style>

<script>
    // Wait until the DOM is fully loaded
    window.onload = function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var notify = document.getElementById('notification');
            if (notify) {
                notify.style.display = 'block';
                setTimeout(function() {
                    notify.style.display = 'none';
                }, 1000);
            }

            // Redirect to task.php after showing notification
            setTimeout(function() {
                window.location.href = 'task.php';
            }, 1000);
        });
    }
</script>

</head>
<body>

    <style>
        body {
            background: #f7f7f7;
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

        .main-content {
            margin-left: 270px;
            padding: 100px 20px 40px;
        }

        /* Inherited form styles */
        .container {
            width: 100%;
            max-width: 1100px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .section-title {
            margin-top: 30px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        ol {
            padding-left: 20px;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #999;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .rating-table td:first-child {
            text-align: left;
        }

        .form-group-inline {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-group-inline > div {
            flex: 1;
            min-width: 200px;
        }

        .submit-btn {
            margin-top: 30px;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #2a6fb3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        .submit-btn:hover {
            background-color: #235a91;
        }

        @media print {
        .navbar, .sidebar, .no-print {
            display: none !important;
        }

        .main-content {
            margin-left: 0;
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
    <a href="task.php"><i class="fas fa-chart-line"></i> Tasks</a>
    <a href="#"><i class="fas fa-file-alt"></i> Application status</a>
    <a href="#"><i class="fas fa-file-alt"></i> Submissions</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <div class="container shadow-lg p-5 bg-white rounded-4">
        <h2 class="text-center mb-3 text-uppercase fw-bold">Borang Penilaian Keberkesanan Kursus / Seminar</h2>
        <p class="text-center text-muted mb-4"><em>Borang ini hendaklah diisi dalam masa tujuh (7) hari bekerja selepas menghadiri kursus/seminar.</em></p>

        <form method="post">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Pegawai/Staf</label>
                    <input type="text" class="form-control" name="nama">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bahagian</label>
                    <input type="text" class="form-control" name="bahagian">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jawatan</label>
                    <input type="text" class="form-control" name="jawatan">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tajuk Kursus/Seminar</label>
                    <input type="text" class="form-control" name="tajuk">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tarikh / Hari</label>
                    <input type="date" class="form-control" name="tarikh">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tempat</label>
                    <input type="text" class="form-control" name="tempat">
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-3">
            <div class="mb-3 page-break">
                <label class="form-label fw-semibold">1. Objektif menghadiri kursus:</label>
                <textarea class="form-control" name="objektif" rows="3"></textarea>
            </div>

            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">2. Lima (5) perkara yang anda pelajari:</label>
                <ol class="ps-3">
                    <li><textarea class="form-control my-2" name="perkara1" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="perkara2" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="perkara3" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="perkara4" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="perkara5" rows="2"></textarea></li>
                </ol>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">3. Cadangan/Penambahbaikan:</label>
                <ol class="ps-3">
                    <li><textarea class="form-control my-2" name="cadangan1" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="cadangan2" rows="2"></textarea></li>
                    <li><textarea class="form-control my-2" name="cadangan3" rows="2"></textarea></li>
                </ol>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold page-break">Penceramah</h5>
            <div class="row g-3 mb-4">

            <div class="row g-3 mb-4">
                <div class="col"><input type="text" class="form-control" name="p1" placeholder="Penceramah 1"></div>
                <div class="col"><input type="text" class="form-control" name="p2" placeholder="Penceramah 2"></div>
                <div class="col"><input type="text" class="form-control" name="p3" placeholder="Penceramah 3"></div>
                <div class="col"><input type="text" class="form-control" name="p4" placeholder="Penceramah 4"></div>
                <div class="col"><input type="text" class="form-control" name="p5" placeholder="Penceramah 5"></div>
            </div>

            <p class="text-muted">Skala penilaian: <strong>10–9: Cemerlang, 8–6: Baik, 5–4: Sederhana, 3–1: Lemah</strong></p>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Aspek</th>
                            <th>P1</th><th>P2</th><th>P3</th><th>P4</th><th>P5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $aspects = [
                            "i. Kefahaman/Penguasaan terhadap subjek",
                            "ii. Penyampaian",
                            "iii. Penyediaan bahan/slaid",
                            "iv. Perhubungan dan penglibatan peserta",
                            "v. Penggunaan contoh-contoh yang diselitkan dalam ceramah"
                        ];
                        foreach ($aspects as $index => $text) {
                            echo "<tr>";
                            echo "<td class='text-start'>$text</td>";
                            for ($i = 1; $i <= 5; $i++) {
                                echo "<td><input type='text' class='form-control text-center' name='aspect{$index}_p{$i}' style='width: 60px; margin: auto;'></td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tandatangan</label>
                    <input type="text" class="form-control" name="tandatangan">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tarikh</label>
                    <input type="date" class="form-control" name="tarikh_penilaian">
                </div>
            </div>
            <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm no-print">Submit</button>
            <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold rounded-pill shadow-sm no-print" onclick="window.print()">Print</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
