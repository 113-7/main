<<<<<<< HEAD

<?php
// 資料庫
include('database_link.php');



// 查詢資料庫，根據學系 ID 獲取詳細資料
$sql = "SELECT * FROM departments "; // 使用 department_id 來查詢
$result = $conn->query($sql);

// 檢查是否有資料
if ($result->num_rows > 0) {
    // 取得所有學系資料
    $departments = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($departments, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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
=======

<?php
// 資料庫
include('database_link.php');



// 查詢資料庫，根據學系 ID 獲取詳細資料
$sql = "SELECT * FROM departments "; // 使用 department_id 來查詢
$result = $conn->query($sql);

// 檢查是否有資料
if ($result->num_rows > 0) {
    // 取得所有學系資料
    $departments = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($departments, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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
>>>>>>> 91c4379 (上傳測試)
