<?php
session_start();
include 'database_link.php';
//前端用include這裡要確定來源
header("Access-Control-Allow-Origin: http://localhost:8080");

// 如果是 OPTIONS 請求，直接回 200 結束（這是預檢請求）
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

$student_id = $_SESSION['user_id'];
$title = $input['title'] ?? '';
$content = $input['content'] ?? '';
$tags = $input['tags'] ?? '';

// 檢查必要欄位是否有值
if (!$student_id || !$title || !$content) {
    http_response_code(400);
    echo json_encode(['error' => '缺少必要參數'. $student_id . $title . $content. $tags]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO posts (student_id, title, content,tags) VALUES (?, ?, ?,?)");
$stmt->bind_param("isss", $student_id, $title, $content, $tags);

if ($stmt->execute()) {
    echo json_encode("問題已成功新增。");
} else {
    http_response_code(500);
    // 這裡要先組成陣列，再用 json_encode
    echo json_encode(['error' => "新增問題失敗：" . $conn->error]);
}

$stmt->close();
$conn->close();
?>
