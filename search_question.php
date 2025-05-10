<?php
require_once 'database_link.php';

$keyword = $_GET['keyword'] ?? '';

if (empty($keyword)) {
    http_response_code(400);
    echo "請提供搜尋關鍵字。";
    exit;
}

$keyword = "%$keyword%";
$stmt = $conn->prepare("SELECT question_id, title, content, created_at FROM questions WHERE title LIKE ? OR content LIKE ?");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($questions, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
