<?php
session_start();

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: index.php');
    exit;
}

// Get form data with validation
$first_name = htmlspecialchars($_POST['first_name'] ?? '');
$middle_name = htmlspecialchars($_POST['middle_name'] ?? '');
$last_name = htmlspecialchars($_POST['last_name'] ?? '');
$age = htmlspecialchars($_POST['age'] ?? '');
$gender = htmlspecialchars($_POST['gender'] ?? '');
$hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : [];
$bio = htmlspecialchars($_POST['bio'] ?? '');

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($age) || empty($gender)) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Display</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --c1: #F0E491;
            --c2: #BBC863;
            --c3: #658C58;
            --c4: #31694E;
        }
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, var(--c4), var(--c3), var(--c2), var(--c1));
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            padding: 20px;
            color: #333;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Content Animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.6s ease-out;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: var(--c4);
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(49, 105, 78, 0.12);
        }

        .logo i {
            font-size: 32px;
            color: white;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .info-section {
            margin-bottom: 30px;
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(26, 41, 128, 0.1);
            transition: all 0.3s ease;
        }

        .info-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(26, 41, 128, 0.1);
        }

        .info-section:nth-child(2) { animation-delay: 0.2s; }
        .info-section:nth-child(3) { animation-delay: 0.4s; }
        .info-section:nth-child(4) { animation-delay: 0.6s; }
        .info-section:nth-child(5) { animation-delay: 0.8s; }

        .info-label {
            font-weight: 600;
            color: var(--c4);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-label i {
            font-size: 20px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .info-content {
            background: white;
            padding: 15px;
            border-radius: 12px;
            line-height: 1.6;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .hobbies-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hobby-tag {
            background: linear-gradient(45deg, rgba(49, 105, 78, 0.1), rgba(187, 200, 99, 0.1));
            color: var(--c4);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(26, 41, 128, 0.1);
            transition: all 0.3s ease;
        }

        .hobby-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 41, 128, 0.1);
        }

        .hobby-tag i {
            color: var(--c2);
        }

        .placeholder-text {
            color: #666;
            font-style: italic;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .placeholder-text i {
            color: rgba(49,105,78,0.6);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(49, 105, 78, 0.12);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(49, 105, 78, 0.16);
        }

        .back-btn i {
            font-size: 20px;
        }

        .nav-section {
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .header h1 {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .hobbies-list {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-id-card"></i>
            </div>
            <h1>User Information Display</h1>
            <p>Here's what you submitted</p>
        </div>

        <div class="info-section">
            <div class="info-label">
                <i class="fas fa-user"></i> Full Name
            </div>
            <div class="info-content">
                <?= $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name ?>
            </div>
        </div>

        <div class="info-section">
            <div class="info-label">
                <i class="fas fa-birthday-cake"></i> Age
            </div>
            <div class="info-content"><?= $age ?> years old</div>
        </div>

        <div class="info-section">
            <div class="info-label">
                <i class="fas fa-venus-mars"></i> Gender
            </div>
            <div class="info-content">
                <?php
                $gLower = strtolower($gender);
                $gIcon = 'genderless';
                $gColor = 'var(--c2)'; // default color
                if ($gLower === 'male') { $gIcon = 'male'; $gColor = 'var(--c4)'; }
                elseif ($gLower === 'female') { $gIcon = 'female'; $gColor = 'var(--c3)'; }
                ?>
                <i class="fas fa-<?= $gIcon ?>" style="color: <?= $gColor ?>; margin-right:8px"></i> <?= htmlspecialchars($gender) ?>
            </div>
        </div>

        <div class="info-section">
            <div class="info-label">
                <i class="fas fa-star"></i> Hobbies
            </div>
            <div class="info-content">
                <?php if (!empty($hobbies)): ?>
                    <div class="hobbies-list">
                        <?php 
                        $hobbyIcons = [
                            'Reading' => 'fas fa-book',
                            'Gaming' => 'fas fa-gamepad',
                            'Cooking' => 'fas fa-utensils',
                            'Traveling' => 'fas fa-plane',
                            'Photography' => 'fas fa-camera'
                        ];
                        foreach($hobbies as $hobby): ?>
                            <span class="hobby-tag">
                                <i class="<?= $hobbyIcons[$hobby] ?? 'fas fa-star' ?>"></i>
                                <?= htmlspecialchars($hobby) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <span class="placeholder-text">
                        <i class="fas fa-info-circle"></i>
                        You have not chosen your hobby.
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-section">
            <div class="info-label">
                <i class="fas fa-pen-fancy"></i> Bio
            </div>
            <div class="info-content">
                <?php if (!empty($bio)): ?>
                    <i class="fas fa-quote-left" style="color: var(--c4); margin-right: 10px; opacity: 0.5"></i>
                    <?= nl2br($bio) ?>
                    <i class="fas fa-quote-right" style="color: var(--c4); margin-left: 10px; opacity: 0.5"></i>
                <?php else: ?>
                    <span class="placeholder-text">
                        <i class="fas fa-info-circle"></i>
                        You have not set your bio.
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="nav-section">
            <a href="index.php" class="back-btn">
                <i class="fas fa-plus-circle"></i>
                Submit Another Response
            </a>
        </div>
    </div>
</body>
</html>