
<?php //按下收藏按鈕後呼叫這個檔案
include('database_link.php');
//前端用include這裡要確定來源
header("Access-Control-Allow-Origin: http://localhost:8080");
session_start();

// 確認使用者是學生
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
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
$stmt = $conn->prepare("INSERT INTO favorite_departments (student_id, department_id) VALUES (?, ?)");
$stmt->bind_param("ii", $student_id, $department_id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "收藏成功"
    ]);
} else {
    error_log("MySQL錯誤碼: " . $conn->errno . ", 錯誤訊息: " . $conn->error);

    if ($conn->errno === 1062) {
        echo json_encode([
            "success" => false,
            "message" => "已經收藏過該學系"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "收藏失敗：" . $conn->error
        ]);
    }
}

$stmt->close();
$conn->close();
?>
