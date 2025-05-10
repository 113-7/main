<?php //按下取消收藏後呼叫這格檔案
session_start();
require_once 'database_link.php';

// 確認使用者為學生
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo "無權限存取此功能。";
    exit;
}

$student_id = $_SESSION['user_id'];
$department_id = $_POST['department_id'] ?? null;

if (!$department_id) {
    http_response_code(400);
    echo "請提供 department_id。";
    exit;
}

// 刪除收藏資料
$stmt = $conn->prepare("DELETE FROM favorites WHERE student_id = ? AND department_id = ?");
$stmt->bind_param("ii", $student_id, $department_id);

if ($stmt->execute()) {
    echo "取消收藏成功。";
} else {
    http_response_code(500);
    echo "取消收藏失敗：" . $conn->error;
}

$stmt->close();
$conn->close();
?>
