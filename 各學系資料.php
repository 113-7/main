<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
?>
<?php
include('database_link.php');
// 資料庫設定
$pdo = new PDO("mysql:host=127.0.0.1;dbname=department_transfer", 'root', '');  // 根據實際資料庫配置修改
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 從 JSON 取得 POST 資料
$data = json_decode(file_get_contents("php://input"), true);

// 取得搜尋條件
$college = isset($data['college']) ? $data['college'] : '';
$grade = isset($data['grade']) ? $data['grade'] : '';
$keyword = isset($data['keyword']) ? $data['keyword'] : '';

// 構建 SQL 查詢
$query = "SELECT * FROM departments WHERE 1=1"; // 預設查詢所有部門資料

if ($college) {
    $query .= " AND faculty LIKE :college";  // 根據學院篩選
}
/*if ($grade) {
    $query .= " AND grade = :grade";  // 根據年級篩選（假設有年級欄位，根據需要調整）
}*/
if ($grade) {
    // 這邊直接加變數名進 SQL，避免綁定欄位名（因為不能用 bindParam 綁定欄位）
    $allowedColumns = ['second_year_quota', 'third_year_quota', 'fourth_year_quota'];
    if (in_array($grade, $allowedColumns)) {
        $query .= " AND `$grade` > 0";  // 注意：這裡用反引號包欄位名，避免 SQL injection
    }
}
if ($keyword) {
    $query .= " AND name LIKE :keyword";  // 根據關鍵字篩選
}

// 準備並執行查詢
$stmt = $pdo->prepare($query);

// 綁定參數
if ($college) $stmt->bindValue(':college', '%' . $college . '%');
if ($grade) $stmt->bindValue(':grade', $grade);
if ($keyword) $stmt->bindValue(':keyword', '%' . $keyword . '%');

$stmt->execute();

// 獲取查詢結果
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 顯示搜尋結果 -->
<?php if ($results && count($results) > 0) {
    echo json_encode([
        "success" => true,
        "data" => $results
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "沒有符合條件的學系資料。"
    ]);
} ?>
