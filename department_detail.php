<?php
// 資料庫
include('database_link.php');

// 檢查是否有傳遞 'department_id' 參數
if (isset($_GET['id'])) {
    // 取得學系 ID
    $department_id = $_GET['id'];  
} else {
    // 如果沒有傳遞學系 ID，顯示錯誤訊息
    die("錯誤：學系 ID 不存在！");
}

// 查詢資料庫，根據學系 ID 獲取詳細資料
$sql = "SELECT * FROM departments WHERE department_id = $department_id"; // 使用 department_id 來查詢
$result = $conn->query($sql);

// 檢查是否有資料
if ($result->num_rows > 0) {
    // 顯示學系的詳細資料
    $department = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($department, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    /*echo "<h2>" . $row['name'] . " - 詳細資料</h2>";
    echo "<p>學系名稱: " . $row['name'] . "</p>";
    echo "<p>學院名稱: " . $row['faculty'] . "</p>";
    echo "<p>學系簡述: " . $row['brief_description'] . "</p>";
    echo "<p>筆試成績比例: " . $row['written_exam_weight'] . "%</p>";
    echo "<p>口試成績比例: " . $row['interview_weight'] . "%</p>";
    echo "<p>資料審查比例: " . $row['review_weight'] . "%</p>";
    echo "<p>備註: " . $row['additional_notes'] . "</p>";*/
} else {
    echo json_encode(["error" => "沒有找到該學系的資料"]);
}

// 關閉資料庫連線
$conn->close();
?>
