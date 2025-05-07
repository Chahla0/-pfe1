<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT users.id as user_id, users.full_name, users.email, 
               specialists.specialty, specialists.phone, 
               specialists.address, specialists.qualification, 
               specialists.experience
        FROM users 
        JOIN specialists ON users.id = specialists.user_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>اختر أخصائي</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .specialist-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .specialist-header {
            font-size: 20px;
            color: #4e73df;
            margin-bottom: 10px;
        }
        .specialist-info {
            margin-bottom: 5px;
            font-size: 16px;
        }
        .view-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1cc88a;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .view-btn:hover {
            background-color: #17a673;
        }
    </style>
</head>
<body>

<h2>اختر أخصائي من القائمة:</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="specialist-card">
        <div class="specialist-header"><?php echo htmlspecialchars($row['full_name']); ?> - <?php echo htmlspecialchars($row['specialty']); ?></div>
        <div class="specialist-info">📧 البريد الإلكتروني: <?php echo htmlspecialchars($row['email']); ?></div>
        <div class="specialist-info">📞 رقم الهاتف: <?php echo htmlspecialchars($row['phone']); ?></div>
        <div class="specialist-info">📍 العنوان: <?php echo htmlspecialchars($row['address']); ?></div>
        <div class="specialist-info">🎓 المؤهل العلمي: <?php echo htmlspecialchars($row['qualification']); ?></div>
        <div class="specialist-info">📅 الخبرة: <?php echo htmlspecialchars($row['experience']); ?> سنوات</div>
        <a class="view-btn" href="#" onclick="openModal(<?php echo $row['user_id']; ?>)">حجز جلسة</a>

    </div>
<?php endwhile; ?>

<?php $conn->close(); ?>
<!-- نموذج الحجز داخل نافذة منبثقة -->
<div id="bookingModal" style="display:none; position:fixed; top:0; right:0; bottom:0; left:0; background:rgba(0,0,0,0.6); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; border-radius:10px; width:300px; position:relative;">
        <h3>معلومات الطفل</h3>
        <form action="submit_booking.php" method="POST">
            <input type="hidden" name="specialist_id" id="specialist_id">

            <label>اسم الطفل:</label>
            <input type="text" name="child_name" required>

            <label>العمر:</label>
            <input type="number" name="child_age" required>

            <label>الحالة:</label>
            <textarea name="child_condition" required></textarea>

            <label>نوع الجلسة:</label>
            <select name="session_type" required>
                <option value="">-- اختر نوع الجلسة --</option>
                <option value="حضوري">حضوري</option>
                <option value="عن بعد">عن بعد</option>
            </select>

            <button type="submit">حجز</button>
        </form>
        <button onclick="closeModal()" style="position:absolute; top:10px; right:10px;">×</button>

    </div>
</div>

<script>
    function openModal(specialistId) {
    document.getElementById('specialist_id').value = specialistId;
    document.getElementById('bookingModal').style.display = 'flex';
}


    function closeModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }
</script>

</body>
</html>
