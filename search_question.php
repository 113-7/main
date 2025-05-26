<?php
include 'database_link.php';


$data = json_decode(file_get_contents('php://input'), true);

$keyword = $data['keyword'] ?? '';
$tag = $data['tag'] ?? '';

$query = "SELECT post_id, title, content, created_at, tags FROM posts WHERE 1=1 ";
$params = [];
$types = "";

// 加入關鍵字條件（title 或 content）
if (!empty($keyword)) {
    $query .= " AND (title LIKE ? OR content LIKE ?)";
    $keywordLike = "%$keyword%";
    $params[] = &$keywordLike;
    $params[] = &$keywordLike;
    $types .= "ss";
}

// 加入 tag 條件（tags 欄位中是否有這個 tag）
if (!empty($tag)) {
    $query .= " AND tags LIKE ?";
    $tagLike = "%$tag%";
    $params[] = &$tagLike;
    $types .= "s";
}

$query .= " ORDER BY created_at DESC";
// 預備查詢
$stmt = $conn->prepare($query);

// 綁定參數
if (!empty($params)) {
    array_unshift($params, $types); // 把 type 字串加到最前面
    call_user_func_array([$stmt, 'bind_param'], $params);
}

$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $row['tags'] = explode(',', $row['tags']);  // 字串變陣列
    $questions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($questions, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
