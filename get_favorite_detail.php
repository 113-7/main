<?php
//從學系id去找這個學系是否收藏過

include('database_link.php');
//前端用include這裡要確定來源
header("Access-Control-Allow-Origin: http://localhost:8080");
session_start();

// 權限檢查：僅限學生
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    http_response_code(403);
    echo json_encode(['error' => '無權限存取此功能。']);
    exit;
}


$student_id = $_SESSION['user_id'];
$department_id = $_GET['department_id'] ?? null;

if (!$department_id) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "缺少 department_id",
        "favorited" => false
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT 1 FROM favorite_departments WHERE student_id = ? AND department_id = ?");
$stmt->bind_param("ii", $student_id, $department_id);
$stmt->execute();
$stmt->store_result();

$isFavorited = $stmt->num_rows > 0;

echo json_encode([
    "success" => true,
    "favorited" => $isFavorited
]);

$stmt->close();
$conn->close();
