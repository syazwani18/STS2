<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Training Management System</title>
    
    <!-- Bootstrap & Icons -->
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 40px;
            min-height: calc(100vh - var(--navbar-height));
        }

        .page-header {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, #a31515 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin: 0;
        }

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .task-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 25px 25px 0 0;
        }

        .task-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        .task-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .task-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .task-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .task-description {
            color: #7f8c8d;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .task-button {
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
            border: none;
            color: #333;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
        }

        .task-button:hover {
            background: linear-gradient(135deg, #ff8f00 0%, #ffc107 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.6);
            color: #333;
        }

        .task-button i {
            margin-right: 8px;
        }

        /* Specific task card colors */
        .task-card:nth-child(1) .task-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .task-card:nth-child(2) .task-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .task-card:nth-child(3) .task-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        /* Floating animation */
        .task-card:nth-child(1) {
            animation: float1 6s ease-in-out infinite;
        }

        .task-card:nth-child(2) {
            animation: float2 6s ease-in-out infinite 2s;
        }

        .task-card:nth-child(3) {
            animation: float3 6s ease-in-out infinite 4s;
        }

        @keyframes float1 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        @keyframes float3 {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 2.2rem;
            }

            .tasks-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .task-card {
                padding: 25px;
            }
        }

        /* Loading animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        .fade-in:nth-child(1) { animation-delay: 0.1s; }
        .fade-in:nth-child(2) { animation-delay: 0.3s; }
        .fade-in:nth-child(3) { animation-delay: 0.5s; }

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
    <!-- Page Header -->
    <div class="page-header fade-in">
        <h1 class="page-title">
            <i class="fas fa-tasks me-3"></i>
            Training Tasks
        </h1>
        <p class="page-subtitle">Choose from available training forms and assessments</p>
    </div>

    <!-- Task Cards -->
    <div class="tasks-grid">
        <div class="task-card fade-in">
            <div class="task-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3 class="task-title">Borang Permohonan Latihan (BPL)</h3>
            <p class="task-description">
                Submit your training application form. This form is required for all training program applications and must be completed before attending any training sessions.
            </p>
            <a href="bpl.php" class="task-button">
                <i class="fas fa-edit"></i>
                Apply Now
            </a>
        </div>

        <div class="task-card fade-in">
            <div class="task-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="task-title">Penilaian Keberkesanan Kursus</h3>
            <p class="task-description">
                Evaluate the effectiveness of completed training courses. Your feedback helps improve future training programs and ensures quality standards.
            </p>
            <a href="pkk.php" class="task-button">
                <i class="fas fa-star"></i>
                Evaluate
            </a>
        </div>

        <div class="task-card fade-in">
            <div class="task-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="task-title">Training Effectiveness Assessment</h3>
            <p class="task-description">
                Complete the comprehensive training effectiveness assessment form. This evaluation measures the impact and success of your training experience.
            </p>
            <a href="tea.php" class="task-button">
                <i class="fas fa-check-circle"></i>
                Assess
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Add smooth scrolling and enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    const cards = document.querySelectorAll('.task-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });

    // Add click ripple effect
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: radial-gradient(circle, rgba(255,255,255,0.6) 0%, transparent 70%);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                z-index: 1;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .task-card {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>