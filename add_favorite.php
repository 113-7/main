
<?php //按下收藏按鈕後呼叫這個檔案
session_start();
require_once 'database_link.php';

// 確認使用者是學生
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

// 插入收藏資料
$stmt = $conn->prepare("INSERT INTO favorites (student_id, department_id) VALUES (?, ?)");
$stmt->bind_param("ii", $student_id, $department_id);

if ($stmt->execute()) {
    echo "收藏成功。";
} else {
    if ($conn->errno === 1062) {
        echo "已經收藏過該學系。";
    } else {
        http_response_code(500);
        echo "收藏失敗：" . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
