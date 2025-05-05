<?php
// 資料庫
include('database_link.php');

// 查詢公告與對應的學系名稱
$sql = "
    SELECT 
        a.announcement_id,
        a.title,
        a.content,
        a.created_at,
        d.name AS department_name
    FROM announcements a
    LEFT JOIN departments d ON a.department_id = d.department_id
    ORDER BY a.created_at DESC
";

$result = $conn->query($sql);

// 檢查是否有資料
if ($result->num_rows > 0) {
    $announcements = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($announcements, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "沒有公告資料"]);
}

// 關閉資料庫連線
$conn->close();
?>