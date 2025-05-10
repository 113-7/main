<?php
session_start();
require_once 'database_link.php';

// 權限檢查：僅限管理員
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo "無權限存取此頁面。";
    exit;
}

// 接收 POST 資料
$application_id = $_POST['application_id'] ?? '';
$status = $_POST['status'] ?? '';
$reviewer_id = $_SESSION['user_id'];

// 資料驗證
$valid_statuses = ['審核中', '已通過', '未通過'];
if (empty($application_id) || !in_array($status, $valid_statuses)) {
    http_response_code(400);
    echo "請提供有效的申請 ID 和狀態。";
    exit;
}

// 更新 application_status
$stmt = $conn->prepare("UPDATE application_status SET status = ?, review_time = NOW(), reviewer_id = ? WHERE application_id = ?");
$stmt->bind_param("ssi", $status, $reviewer_id, $application_id);
if ($stmt->execute()) {
    echo "申請狀態已更新。";
} else {
    http_response_code(500);
    echo "更新失敗：" . $stmt->error;
}
$stmt->close();
$conn->close();
?>
