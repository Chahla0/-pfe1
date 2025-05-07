<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'specialist') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id'];

$sql = "SELECT * FROM sessions WHERE specialist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الجلسات المحجوزة</title>
    <style>
        body { font-family: 'Tahoma'; background: #f0f0f0; padding: 20px; }
        table { width: 100%; background: #fff; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #4e73df; color: white; }
    </style>
</head>
<body>

<h2>الجلسات المحجوزة</h2>

<table>
    <tr>
        <th>اسم الطفل</th>
        <th>العمر</th>
        <th>الحالة</th>
        <th>نوع الجلسة</th>
        <th>الحالة</th>
        <th>تاريخ الحجز</th>
        <th>إجراء</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['child_name']); ?></td>
        <td><?php echo htmlspecialchars($row['child_age']); ?></td>
        <td><?php echo htmlspecialchars($row['child_condition']); ?></td>
        <td><?php echo htmlspecialchars($row['session_type']); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        <td>
            <form method="POST" action="update_session_status.php" style="display:inline;">
                <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                <button type="button" onclick="showSessionForm(<?php echo $row['id']; ?>, '<?php echo $row['session_type']; ?>')">قبول</button>
            </form>

            <form method="POST" action="update_session_status.php" style="display:inline;">
                <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="action" value="rejected">رفض</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div id="sessionForm" style="display:none; position:fixed; top:20%; left:30%; background:white; padding:20px; border:1px solid #ccc;">
    <form method="POST" action="update_session_status.php">
        <input type="hidden" name="session_id" id="session_id">
        <input type="hidden" name="action" value="approved">
        
        <label>تاريخ الجلسة:</label><br>
        <input type="date" name="session_date" required><br><br>
        
        <label>وقت الجلسة:</label><br>
        <input type="time" name="session_time" required><br><br>
        
        <div id="online_fields" style="display:none;">
            <label>رابط الاجتماع (Zoom أو Google Meet):</label><br>
            <input type="url" name="meeting_link"><br><br>
        </div>

        <button type="submit">تأكيد</button>
        <button type="button" onclick="document.getElementById('sessionForm').style.display='none'">إلغاء</button>
    </form>
</div>

<script>
function showSessionForm(id, type) {
    document.getElementById('sessionForm').style.display = 'block';
    document.getElementById('session_id').value = id;

    if (type === 'عن بعد') {
        document.getElementById('online_fields').style.display = 'block';
    } else {
        document.getElementById('online_fields').style.display = 'none';
    }
}
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
