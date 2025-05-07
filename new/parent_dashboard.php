<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: login.html");
    exit();
}


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة ولي الأمر</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
         :root {
            --primary-blue: #85bae2;
            --primary-green: #92cac7;
            --primary-purple: #9999c9;
            --text-dark: #3a3a3a;
            --text-light: #5e5e5e;
            --bg-light: #f8fafc;
            --border-radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
            padding: 30px 0;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }
        
        .profile-section {
            text-align: center;
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(133, 186, 226, 0.2);
            margin-bottom: 25px;
        }
        
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-green);
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(146, 202, 199, 0.2);
        }
        
        .profile-section h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        
        .profile-section p {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Navigation Styles */
        nav ul {
            list-style: none;
            padding: 0 15px;
            flex-grow: 1;
        }
        
        nav li {
            margin-bottom: 5px;
        }
        
        nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        nav a:hover, nav a.active {
            background: linear-gradient(90deg, rgba(133, 186, 226, 0.1) 0%, rgba(146, 202, 199, 0.1) 100%);
            color: var(--primary-blue);
            transform: translateX(-3px);
        }
        
        nav a i {
            margin-left: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Logout Button */
        .logout-btn {
            margin-top: auto;
            padding: 0 15px;
        }
        
        .logout-btn a {
            background-color: rgba(248, 215, 218, 0.3);
            color: #721c24;
        }
        
        .logout-btn a:hover {
            background-color: rgba(248, 215, 218, 0.5);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                padding: 20px 0;
            }
            
            .profile-section {
                padding: 0 15px 15px;
            }
            
            nav ul {
                display: flex;
                flex-wrap: wrap;
                padding: 0 10px;
            }
            
            nav li {
                flex: 1 0 50%;
                margin-bottom: 5px;
            }
            
            nav a {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
            
            .logout-btn {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="profile-section">
            <img src="parent.JPG" alt="صورة الملف الشخصي" class="profile-img">
            <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
            <p>ولي أمر</p>
        </div>

        <nav>
            <ul>
                <li>
                    <a href="child_profile.php" class="active">
                        <i class="fas fa-child"></i>
                        <span>ملف الطفل</span>
                    </a>
                </li>
                <li>
                    <a href="choose-specialist.php">
                        <i class="fas fa-user-md"></i>
                        <span>اختيار أخصائي</span>
                    </a>
                </li>
                <li>
                    <a href="chosen-specialists.php">
                        <i class="fas fa-user-check"></i>
                        <span>الجلسات مع الأخصائيين</span>
                    </a>
                </li>
                <li>
                    <a href="daily_consultation.php">
                        <i class="fas fa-comments"></i>
                        <span>استشارة يومية</span>
                    </a>
                </li>
            </ul>
            
            <div class="logout-btn">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </div>
        </nav>
    </div>
    <div class="content" style="flex-grow: 1; padding: 20px;">
        <h1>مرحبًا بك في لوحة ولي الأمر</h1>
        <p>هنا يمكنك إدارة معلومات طفلك والتواصل مع الأخصائيين.</p>
        <!-- Add more content here as needed -->
    </div>
</body>
</html>
