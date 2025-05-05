<?php
// 允許跨域請求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header('Content-Type: application/json');

session_start(); // 開啟 Session
include('database_link.php'); // 引入資料庫連線檔案

// 檢查是否有會話資料
if (isset($_SESSION['department_id'])) {
    $department_id = $_SESSION['department_id'];

    // 查詢學系名稱
    $query = "SELECT name FROM departments WHERE department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['department_name'] = $row['name']; // 將學系名稱存入 session
    } else {
        $_SESSION['department_name'] = null; // 如果找不到學系名稱
    }

    $stmt->close();
}


// 檢查是否有會話資料
if (isset($_SESSION['user_id'])) {
    // 回傳 session 資料
    echo json_encode([
        'status' => 'success',
        'message' => '會話資料獲取成功',
        'session' => [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'department_id' => $_SESSION['department_id'],
            'department_name' => $_SESSION['department_name']
        ]
    ]);
} else {
    // 如果沒有會話資料，回傳錯誤
    echo json_encode(['status' => 'error', 'message' => '沒有登入的會話']);
}
?>