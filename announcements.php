<?php
include('database_link.php');

$departmentid = isset($_GET['department']) ? $_GET['department'] : '';

if ($departmentid) {
    $stmt = $conn->prepare("
        SELECT 
            a.announcement_id,
            a.title,
            a.content,
            a.created_at,
            d.name AS department_name
        FROM announcements a
        LEFT JOIN departments d ON a.department_id = d.department_id
        WHERE d.department_id = ?
        ORDER BY a.created_at DESC
    ");
    $stmt->bind_param("i", $departmentid); // 用 department_id 的數字比對，不是 name
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT 
            a.announcement_id,
            a.title,
            a.content,
            a.created_at,
            d.name AS department_name
        FROM announcements a
        LEFT JOIN departments d ON a.department_id = d.department_id
        ORDER BY a.created_at DESC
    ");
}

if ($result->num_rows > 0) {
    $announcements = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($announcements, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "沒有公告資料"]);
}

$conn->close();
?>