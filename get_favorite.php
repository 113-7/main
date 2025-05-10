<?php
session_start();
require_once 'database_link.php';

// 權限檢查：僅限學生
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo json_encode(['error' => '無權限存取此功能。']);
    exit;
}

$student_id = $_SESSION['user_id'];

// 查詢收藏的學系資訊
$sql = "
    SELECT d.department_id, d.name, d.description
    FROM favorites f
    JOIN departments d ON f.department_id = d.department_id
    WHERE f.student_id = ?
    ORDER BY d.name ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$favorites = [];
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}

header('Content-Type: application/json');
echo json_encode($favorites, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
