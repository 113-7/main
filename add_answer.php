<?php
session_start();
require_once 'database_link.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo "無權限存取此功能。";
    exit;
}

$student_id = $_SESSION['user_id'];
$question_id = $_POST['question_id'] ?? null;
$content = $_POST['content'] ?? '';

if (!$question_id || empty($content)) {
    http_response_code(400);
    echo "請提供問題 ID 與回答內容。";
    exit;
}

$stmt = $conn->prepare("INSERT INTO answers (question_id, student_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $question_id, $student_id, $content);

if ($stmt->execute()) {
    echo "回答已成功新增。";
} else {
    http_response_code(500);
    echo "新增回答失敗：" . $conn->error;
}

$stmt->close();
$conn->close();
?>
