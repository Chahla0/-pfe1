<?php
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$specialist_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT c.*, u.full_name FROM consultations c JOIN users u ON u.id = c.parent_id WHERE c.specialist_id = ? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $specialist_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>الرد على الاستشارات</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
    echo "<p><strong>الولي:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
    echo "<p><strong>الحالة:</strong> " . nl2br(htmlspecialchars($row['child_state'])) . "</p>";
    
    if ($row['reply']) {
        echo "<p><strong>ردك:</strong> " . nl2br(htmlspecialchars($row['reply'])) . "</p>";
    } else {
        echo "<form method='POST' action='reply_consultation.php'>";
        echo "<input type='hidden' name='consultation_id' value='{$row['id']}'>";
        echo "<textarea name='reply' rows='3' cols='50' required></textarea><br>";
        echo "<button type='submit'>إرسال الرد</button>";
        echo "</form>";
    }
    echo "</div>";
}
