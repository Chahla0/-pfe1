<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الأخصائي</title>
    <style>
        body {
            font-family: 'Tahoma';
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 220px;
            background-color: #4e73df;
            color: white;
            padding: 20px;
            height: 100vh;
        }
        .sidebar a {
            display: block;
            color: white;
            margin: 15px 0;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .content {
            padding: 30px;
            flex-grow: 1;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>القائمة</h3>
    <a href="specialist_dashboard.php?page=home">الرئيسية</a>
    <a href="specialist_dashboard.php?page=profile">الملف الشخصي</a>
    <a href="specialist_dashboard.php?page=child_files">ملفات الطفل</a> 
    <a href="specialist_dashboard.php?page=sessions">الجلسات المحجوزة</a>
    <a href="specialist_dashboard.php?page=consultations">الرد على الاستشارات</a>
    <a href="logout.php">تسجيل الخروج</a>



</div>

<div class="content">
    <?php
    $page = $_GET['page'] ?? 'profile';
    if ($page === 'profile') {
        include 'specialist_profile.php';
    } elseif ($page === 'sessions') {
        include 'specialist_sessions.php';
    }elseif ($page === 'consultations') {
        include 'specialist_consultations.php';
    }elseif ($page === 'child_files') {
        include 'specialist_child_files.php';
    }elseif ($page === 'home') {
        include 'specialist_home.php';
    }
    else {
        echo "الصفحة غير موجودة.";
    }
    ?>
</div>

</body>
</html>
