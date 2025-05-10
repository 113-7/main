<?php
session_start();
require_once 'database_link.php';

// 權限檢查：僅限學生
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo "無權限存取此頁面。";
    exit;
}

// 接收 POST 資料
$student_id = $_SESSION['user_id'];
$student_name = $_POST['student_name'] ?? '';
$department_id = $_POST['department_id'] ?? '';
$reason = $_POST['reason'] ?? '';

// 資料驗證
if (empty($student_name) || empty($department_id) || empty($reason)) {
    http_response_code(400);
    echo "請填寫所有必要欄位。";
    exit;
}

// 插入 application_data
$stmt = $conn->prepare("INSERT INTO application_data (student_id, student_name, department_id, reason) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $student_id, $student_name, $department_id, $reason);
if ($stmt->execute()) {
    $application_id = $stmt->insert_id;

    // 插入 application_status(初始狀態
    $status_stmt = $conn->prepare("INSERT INTO application_status (application_id, status) VALUES (?, '審核中')");
    $status_stmt->bind_param("i", $application_id);
    $status_stmt->execute();
    $status_stmt->close();

    echo "申請已成功提交。";
} else {
    http_response_code(500);
    echo "申請提交失敗：" . $stmt->error;
}
$stmt->close();
$conn->close();
?>
