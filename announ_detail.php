<?php
// 資料庫
include('database_link.php');

// 檢查是否有傳遞 'announcement_id' 參數
if (isset($_GET['id'])) {
    // 取得公告 ID
    $announcement_id = $_GET['id'];  
} else {
    // 如果沒有傳遞公告 ID，顯示錯誤訊息
    die("錯誤：公告 ID 不存在！");
}

// 查詢資料庫，根據公告 ID 獲取詳細資料
$sql = "
    SELECT 
        a.announcement_id,
        a.title,
        a.content,
        a.created_at,
        d.name AS department_name
    FROM announcements a
    LEFT JOIN departments d ON a.department_id = d.department_id
    WHERE a.announcement_id = $announcement_id
";
$result = $conn->query($sql);

// 檢查是否有資料
if ($result->num_rows > 0) {
    // 顯示公告的詳細資料
    $announcement = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($announcement, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "沒有找到該公告的資料"]);
}

// 關閉資料庫連線
$conn->close();
?>
