<?php
session_start();
$conn = new mysqli("localhost", "root", "", "speech_therapy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$parent_id = $_SESSION['user_id'];
$specialist_id = $_POST['specialist_id'];
$child_name = $_POST['child_name'];
$child_age = $_POST['child_age'];
$child_condition = $_POST['child_condition'];
$session_type = $_POST['session_type'];


$stmt = $conn->prepare("INSERT INTO sessions (specialist_id, parent_id, child_name, child_age, child_condition, session_type, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("iissss", $specialist_id, $parent_id, $child_name, $child_age, $child_condition, $session_type);


if ($stmt->execute()) {
    echo "تم إرسال الطلب بنجاح!";
} else {
    echo "حدث خطأ: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
