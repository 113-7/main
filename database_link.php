<?php
header("Access-Control-Allow-Origin: *"); // 允許所有來源的請求
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");


$host = "localhost";  
$user = "root";       
$password = "";      
$dbname = "department_transfer";  // 資料庫名稱

//連線
$conn = new mysqli($host, $user, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
} else {
}

?>
