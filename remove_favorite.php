<?php //按下取消收藏後呼叫這格檔案
include('database_link.php');
//前端用include這裡要確定來源
header("Access-Control-Allow-Origin: http://localhost:8080");
session_start();

// 確認使用者為學生
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

// 刪除收藏資料
$stmt = $conn->prepare("DELETE FROM favorite_departments WHERE student_id = ? AND department_id = ?");
$stmt->bind_param("ii", $student_id, $department_id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "取消收藏成功"
    ]);
} else {
    error_log("MySQL錯誤碼: " . $conn->errno . ", 錯誤訊息: " . $conn->error);

    if ($conn->errno === 1062) {
        echo json_encode([
            "success" => false,
            "message" => "無收藏過該學系"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "取消收藏失敗：" . $conn->error
        ]);
    }
}

$stmt->close();
$conn->close();
?>
