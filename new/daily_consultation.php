<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$parent_id = $_SESSION['user_id'];
?>

<div class="child-profile">
    <h2>استشارة يومية</h2>
    <form method="POST" action="submit_consultation.php">
        <label>اكتب حالة طفلك:</label><br>
        <textarea name="child_state" rows="5" cols="50" required></textarea><br><br>
        <label>اختر الأخصائي:</label>
        <select name="specialist_id" required>
            <?php
            $stmt = $conn->prepare("SELECT sp.user_id, u.full_name 
                FROM sessions s 
                JOIN specialists sp ON s.specialist_id = sp.user_id 
                JOIN users u ON sp.user_id = u.id 
                WHERE s.parent_id = ? AND s.status = 'approved' 
                GROUP BY sp.user_id");
            $stmt->bind_param("i", $parent_id);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                echo "<option value='{$row['user_id']}'>{$row['full_name']}</option>";
            }
            ?>
        </select><br><br>
        <button type="submit">إرسال الاستشارة</button>
    </form>
</div>

<div class="child-profile">
    <h2>الردود على الاستشارات</h2>
    <?php
        $stmt = $conn->prepare("SELECT c.*, u.full_name 
            FROM consultations c 
            JOIN users u ON c.specialist_id = u.id 
            WHERE c.parent_id = ? 
            ORDER BY c.created_at DESC");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
            echo "<p><strong>إلى الأخصائي:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
            echo "<p><strong>حالة الطفل:</strong> " . nl2br(htmlspecialchars($row['child_state'])) . "</p>";
            echo "<p><strong>الرد:</strong> " . ($row['reply'] ? nl2br(htmlspecialchars($row['reply'])) : "لم يتم الرد بعد") . "</p>";
            echo "</div>";
        }
    ?>
</div>
