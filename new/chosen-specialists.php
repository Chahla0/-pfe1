<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
$parent_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT sp.user_id AS specialist_id, u.full_name, sp.specialty
        FROM sessions s
        JOIN specialists sp ON s.specialist_id = sp.user_id
        JOIN users u ON u.id = sp.user_id
        WHERE s.parent_id = ? AND s.status = 'approved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="child-profile">
    <h2>الجلسات مع الأخصائيين</h2>
    <?php if ($result->num_rows === 0): ?>
        <div class="no-data">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>لا توجد جلسات موافق عليها.</h3>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap;">
            <?php while ($row = $result->fetch_assoc()):
                $specialist_id = $row['specialist_id']; ?>
                <div class="specialist-card" style="border: 1px solid #ccc; padding: 15px; margin: 10px; width: 100%;">
                    <h3><?php echo $row['full_name']; ?> - <?php echo $row['specialty']; ?></h3>

                    <!-- قسم الجلسات لهذا الأخصائي -->
                    <h4>الجلسات:</h4>
                    <?php
                    $session_sql = "SELECT * FROM sessions WHERE parent_id = ? AND specialist_id = ? AND status = 'approved'";
                    $session_stmt = $conn->prepare($session_sql);
                    $session_stmt->bind_param("ii", $parent_id, $specialist_id);
                    $session_stmt->execute();
                    $sessions = $session_stmt->get_result();

                    if ($sessions->num_rows > 0) {
                        while ($session = $sessions->fetch_assoc()) {
                            ?>
                            <div class="session-info" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 10px;">
                                <p>📅 التاريخ: <?php echo $session['session_date']; ?></p>
                                <p>⏰ الوقت: <?php echo $session['session_time']; ?></p>
                                <?php if (!empty($session['meeting_link'])): ?>
                                    <p>🔗 <a href="<?php echo $session['meeting_link']; ?>" target="_blank">رابط الجلسة</a></p>
                                <?php endif; ?>
                            </div>
                        <?php
                        }
                    } else {
                        echo "<p>لا توجد جلسات حالياً مع هذا الأخصائي.</p>";
                    }
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
