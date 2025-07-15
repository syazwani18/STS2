<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Training Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #800000;
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 40px;
            min-height: calc(100vh - var(--navbar-height));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-container {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
            backdrop-filter: blur(15px);
            border-radius: 30px;
            padding: 50px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 30px 30px 0 0;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-avatar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .profile-avatar i {
            font-size: 60px;
            color: white;
            z-index: 2;
            position: relative;
        }

        .profile-name {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .profile-role {
            color: #6c757d;
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .info-label i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .info-value {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 500;
            margin: 0;
        }

        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(128, 0, 0, 0.6);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            color: #6c757d;
            border: 2px solid #dee2e6;
        }

        .btn-outline-custom:hover {
            background: #f8f9fa;
            color: #495057;
            transform: translateY(-2px);
        }

        .btn-custom i {
            margin-right: 8px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .profile-container {
                padding: 30px 25px;
            }

            .profile-name {
                font-size: 2rem;
            }

            .profile-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .profile-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
                max-width: 300px;
            }
        }

        /* Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<?php include '../staff/navbar.php'; ?>

<div class="main-content">
    <div class="profile-container fade-in">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="profile-name"><?php echo htmlspecialchars($user['fullname']); ?></h1>
            <p class="profile-role"><?php echo ucwords(str_replace('_', ' ', htmlspecialchars($user['role']))); ?></p>
        </div>

        <div class="profile-info">
            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-envelope"></i>
                    Email Address
                </div>
                <p class="info-value"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-phone"></i>
                    Phone Number
                </div>
                <p class="info-value"><?php echo htmlspecialchars($user['phone_number']); ?></p>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-user-tag"></i>
                    Role
                </div>
                <p class="info-value"><?php echo ucwords(str_replace('_', ' ', htmlspecialchars($user['role']))); ?></p>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-calendar-alt"></i>
                    Member Since
                </div>
                <p class="info-value"><?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>

        <div class="profile-actions">
            <a href="dashboard.php" class="btn-custom btn-outline-custom">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
            <a href="edit_profile.php" class="btn-custom btn-primary-custom">
                <i class="fas fa-edit"></i>
                Edit Profile
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    const container = document.querySelector('.profile-container');
    setTimeout(() => {
        container.style.opacity = '1';
        container.style.transform = 'translateY(0)';
    }, 100);

    // Add hover effects to info cards
    const infoCards = document.querySelectorAll('.info-card');
    infoCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>

</body>
</html>