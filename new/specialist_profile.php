<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id']; // أو أي معرف أخصائي

$sql = "SELECT users.full_name, users.email, specialists.phone, specialists.address, 
               specialists.qualification, specialists.experience, specialists.specialty
        FROM users 
        JOIN specialists ON users.id = specialists.user_id 
        WHERE users.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();
$specialist = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }
        .profile-card {
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 400px;
            margin: auto;
        }
        .profile-card h2 {
            color: #4e73df;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            margin-top: 15px;
        }
        .info-value {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>د. <?php echo htmlspecialchars($specialist['full_name']); ?></h2>
        <div class="info-label">أخصائي <?php echo htmlspecialchars($specialist['specialty']); ?></div>

        <div class="info-label">البريد الإلكتروني</div>
        <div class="info-value"><?php echo htmlspecialchars($specialist['email']); ?></div>

        <div class="info-label">رقم الهاتف</div>
        <div class="info-value"><?php echo htmlspecialchars($specialist['phone']); ?></div>

        <div class="info-label">العنوان</div>
        <div class="info-value"><?php echo htmlspecialchars($specialist['address']); ?></div>

        <div class="info-label">المؤهل العلمي</div>
        <div class="info-value"><?php echo htmlspecialchars($specialist['qualification']); ?></div>

        <div class="info-label">سنوات الخبرة</div>
        <div class="info-value"><?php echo htmlspecialchars($specialist['experience']); ?> سنوات</div>
    </div>
</body>
</html>
