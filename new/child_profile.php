<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");

$childData = [];
$stmt = $conn->prepare("SELECT child_name, child_age, autism_level FROM parents WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $childData = $result->fetch_assoc();
}

$child_name = $childData['child_name'] ?? 'غير مسجل';
$child_age = $childData['child_age'] ?? 'غير مسجل';
$autism_level = $childData['autism_level'] ?? 'غير محدد';
?>

<div class="child-profile">
    <?php if (!empty($childData)): ?>
        <img src="child-avatar.jpg" alt="صورة الطفل" class="child-avatar">
        <div class="child-info">
            <h3><i class="fas fa-child"></i> بيانات الطفل</h3>
            <p><strong>اسم الطفل:</strong> <?php echo htmlspecialchars($child_name); ?></p>
            <p><strong>العمر:</strong> <?php echo htmlspecialchars($child_age); ?> سنوات</p>
            <p><strong>مستوى التوحد:</strong> <?php echo htmlspecialchars($autism_level); ?></p>
        </div>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>لا توجد بيانات للطفل مسجلة</h3>
            <a href="edit_child_profile.php" class="add-btn"><i class="fas fa-plus"></i> إضافة بيانات الطفل</a>
        </div>
    <?php endif; ?>
</div>
