<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the User Info Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --c1: #F0E491; /* light */
            --c2: #BBC863; /* light-mid */
            --c3: #658C58; /* mid */
            --c4: #31694E; /* dark */
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

        /* Form Animation */
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

        /* Container Styles */
        .container {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.6s ease-out;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 40px;
            color: var(--c4);
            position: relative;
        }

        .logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 20px rgba(49, 105, 78, 0.12);
            position: relative;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(49, 105, 78, 0.16);
        }

        .logo i {
            font-size: 40px;
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.06));
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

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i.field-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 18px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            gap: 8px;
            height: 24px;
        }

        label i {
            color: var(--c4);
            font-size: 16px;
            width: 20px;
            text-align: center;
            margin-right: 4px;
        }

        .required-star {
            color: #ff416c;
            margin-left: 4px;
        }

        .optional-text {
            color: #6b7280;
            font-size: 13px;
            font-weight: 400;
            margin-left: 6px;
        }

        .input-field {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--c4);
            background: white;
            box-shadow: 0 5px 15px rgba(49, 105, 78, 0.08);
        }

        .input-field:focus + i {
            color: var(--c4);
        }

        /* Name Grid */
        .name-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        /* Options Styles */
        .options-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 12px;
            padding: 5px;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(49, 105, 78, 0.06);
        }

        .option-item:hover {
            background: rgba(49, 105, 78, 0.08);
        }

        .option-item i {
            font-size: 18px;
            color: var(--c4);
            width: 24px;
            text-align: center;
        }

        .option-item input[type="radio"],
        .option-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--c4);
            cursor: pointer;
        }

        /* Textarea Styles */
        textarea.input-field {
            min-height: 100px;
            resize: vertical;
        }

        select.input-field {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 18px;
            padding-right: 40px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(49, 105, 78, 0.12);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(49, 105, 78, 0.2);
        }

        .submit-btn i {
            font-size: 20px;
        }

        .form-section-title {
            color: var(--c4);
            font-size: 20px;
            font-weight: 600;
            margin: 40px 0 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(49, 105, 78, 0.08);
            position: relative;
        }

        .form-section-title:first-of-type {
            margin-top: 10px;
        }

        .form-section-title i {
            font-size: 24px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, var(--c4), var(--c2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            border-radius: 10px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(49, 105, 78, 0.06);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .name-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .options-group {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-user-circle"></i>
            </div>
            <h1>Welcome to the User Info Form</h1>
            <p>Please fill in your information below</p>
        </div>

        <form action="home.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="form-section-title">
                <i class="fas fa-user"></i> Personal Information
            </div>

            <!-- Name Fields (single icon in section title only) -->
            <div class="name-grid">
                <div class="form-group">
                    <label for="first_name">
                        First Name<span class="required-star">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" class="input-field" required>
                </div>
                <div class="form-group">
                    <label for="middle_name">
                        Middle Name <span class="optional-text">(optional)</span>
                    </label>
                    <input type="text" id="middle_name" name="middle_name" class="input-field">
                </div>
                <div class="form-group">
                    <label for="last_name">
                        Last Name<span class="required-star">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" class="input-field" required>
                </div>
            </div>

            <div class="form-section-title">
                <i class="fas fa-info-circle"></i> Additional Details
            </div>

            <!-- Age Selection -->
            <div class="form-group">
                <label for="age">
                    <i class="fas fa-birthday-cake"></i> Age<span class="required-star">*</span>
                </label>
                <select id="age" name="age" class="input-field" required>
                    <option value="">Select your age</option>
                    <?php for($i = 18; $i <= 60; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Gender Selection -->
            <div class="form-group">
                <label>
                    <i class="fas fa-venus-mars"></i> Gender<span class="required-star">*</span>
                </label>
                <div class="options-group">
                    <label class="option-item">
                        <input type="radio" name="gender" value="Male" required>
                        <i class="fas fa-male" style="color: var(--c4)"></i> Male
                    </label>
                    <label class="option-item">
                        <input type="radio" name="gender" value="Female">
                        <i class="fas fa-female" style="color: var(--c2)"></i> Female
                    </label>
                </div>
            </div>

            <div class="form-section-title">
                <i class="fas fa-heart"></i> Interests & About
            </div>

            <!-- Hobbies -->
            <div class="form-group">
                <label>
                    <i class="fas fa-star"></i> Hobbies
                </label>
                <div class="options-group">
                    <?php
                    $hobbies = [
                        ['Reading', 'fas fa-book'],
                        ['Gaming', 'fas fa-gamepad'],
                        ['Cooking', 'fas fa-utensils'],
                        ['Traveling', 'fas fa-plane'],
                        ['Photography', 'fas fa-camera']
                    ];
                    foreach($hobbies as [$hobby, $icon]):
                    ?>
                    <label class="option-item">
                        <input type="checkbox" name="hobbies[]" value="<?= htmlspecialchars($hobby) ?>">
                        <i class="<?= $icon ?>"></i> <span style="color: var(--c4)"><?= htmlspecialchars($hobby) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bio -->
            <div class="form-group">
                <label for="bio">
                    <i class="fas fa-pen-fancy"></i> Bio
                </label>
                <textarea id="bio" name="bio" class="input-field" placeholder="Tell us about yourself..."></textarea>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i>
                Submit Information
            </button>
        </form>
    </div>
</body>
</html>