<?php
include('database_link.php'); // 假設這是包含資料庫連接的檔案
session_start();

// 從前端接收 JSON 數據
$data = json_decode(file_get_contents('php://input'), true);

// 從 session 中取得學系 ID
$department_id = $_SESSION['department_id']; // 從 session 取得學系 ID
$title = $data['title']; // 取得公告標題
$content = $data['content']; // 取得公告內容

// 準備 SQL 查詢，使用預處理語句防止 SQL 注入
$sql = "INSERT INTO announcements (department_id, title, content) VALUES (?,  ?, ?)";

// 使用預處理語句
$stmt = $conn->prepare($sql);

// 綁定參數
$stmt->bind_param("iss", $department_id,  $title, $content);

// 執行查詢
$success = $stmt->execute();

// 回應前端
echo json_encode([
    "status" => $success ? "success" : "error",
    "message" => $success ? "新增成功" : "新增失敗"
]);

// 關閉連接
$stmt->close();
$conn->close();
?>