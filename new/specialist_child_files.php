<?php
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialist_id = $_SESSION['user_id'];

$sql = "SELECT id, child_name, child_age, child_condition, session_type, session_date 
        FROM sessions 
        WHERE specialist_id = ? 
        AND status = 'approved' ";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ملفات الطفل</title>
    <style>
        body {
            font-family: 'Tahoma';
            background-color: #f4f4f4;
            padding: 20px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card h4 {
            margin-top: 0;
            color: #4e73df;
        }
        .card p {
            margin: 5px 0;
        }
        .card .session-type {
            font-weight: bold;
            color: #1cc88a;
        }
    </style>
</head>
<body>

<h2>ملفات الطفل</h2>
<div class="card-container">
    <?php 
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <h4>الطفل: <?php echo htmlspecialchars($row['child_name']); ?></h4>
            <p><strong>العمر:</strong> <?php echo htmlspecialchars($row['child_age']); ?> سنة</p>
            <p><strong>الحالة:</strong> <?php echo htmlspecialchars($row['child_condition']); ?></p>
            <p><strong>نوع الجلسة:</strong> <span class="session-type"><?php echo htmlspecialchars($row['session_type']); ?></span></p>
            <p><strong>تاريخ الجلسة:</strong> <?php echo htmlspecialchars($row['session_date']); ?></p>
            <p><strong>رقم الجلسة:</strong> <?php echo htmlspecialchars($row['id']); ?></p>
            <!-- زر إضافة جلسة -->
            <button onclick="openSessionForm(<?php echo $row['id']; ?>)">إضافة جلسة</button>

            <!-- النموذج المنبثق لإضافة جلسة -->
            <div id="sessionModal-<?php echo $row['id']; ?>" class="session-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
                <div style="background:white; padding:20px; border-radius:10px;">
                    <h3>إضافة جلسة جديدة</h3>
                    <form method="POST" action="add_session.php">
                        <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                        <label>التاريخ:</label>
                        <input type="date" name="session_date" required><br><br>
                        <label>الوقت:</label>
                        <input type="time" name="session_time" required><br><br>
                        <label>رابط الاجتماع (إن وُجد):</label>
                        <input type="url" name="meeting_link"><br><br>
                        <button type="submit">حفظ الجلسة</button>
                        <button type="button" onclick="closeSessionForm(<?php echo $row['id']; ?>)">إلغاء</button>
                    </form>
                </div>
            </div>

        </div>

    <?php 
        endwhile;
    else:
        echo "<p>لا توجد بيانات لعرضها.</p>";
    endif;
    ?>
</div>
<script>
function openSessionForm(id) {
    document.getElementById('sessionModal-' + id).style.display = 'flex';
}

function closeSessionForm(id) {
    document.getElementById('sessionModal-' + id).style.display = 'none';
}
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
