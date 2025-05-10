<?php
session_start();
require_once 'database_link.php';

// 權限檢查：僅限管理員
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo "無權限存取此頁面。";
    exit;
}

$department_id = $_GET['department_id'] ?? '';

// 資料驗證
if (empty($department_id)) {
    http_response_code(400);
    echo "請提供有效的學系 ID。";
    exit;
}

// 查詢特定學系的所有申請資料及其審核狀態
$sql = "
    SELECT ad.id, ad.student_id, ad.student_name, d.name AS department_name, ad.reason, ad.submit_time, 
           ast.status, ast.review_time, ast.reviewer_id
    FROM application_data ad
    JOIN department d ON ad.department_id = d.id
    LEFT JOIN application_status ast ON ad.id = ast.application_id
    WHERE ad.department_id = ?
    ORDER BY ad.submit_time DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($applications, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
<?php
session_start();
require_once 'database_link.php';

// 權限檢查：僅限管理員
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo "無權限存取此頁面。";
    exit;
}

$department_id = $_GET['department_id'] ?? '';

// 資料驗證
if (empty($department_id)) {
    http_response_code(400);
    echo "請提供有效的學系 ID。";
    exit;
}

// 查詢特定學系的所有申請資料跟審核狀態
$sql = "
    SELECT ad.id, ad.student_id, ad.student_name, d.name AS department_name, ad.reason, ad.submit_time, 
           ast.status, ast.review_time, ast.reviewer_id
    FROM application_data ad
    JOIN department d ON ad.department_id = d.id
    LEFT JOIN application_status ast ON ad.id = ast.application_id
    WHERE ad.department_id = ?
    ORDER BY ad.submit_time DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($applications, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
