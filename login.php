<?php
session_start();

// 資料庫連線設定
$pdo = new PDO("mysql:host=127.0.0.1;dbname=department_transfer", 'root', ''); // 根據你的設定修改
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 確認是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取帳號與密碼
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查詢資料庫中的用戶資料
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // 檢查是否找到了該用戶並且密碼驗證成功
    if ($user && password_verify($password, $user['password'])) {
        // 登入成功，儲存用戶資料到 session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['department_id'] = $user['department_id'];
        
        // 回傳登入成功訊息
        echo json_encode(['status' => 'success', 'message' => '登入成功']);
    } else {
        // 登入失敗，回傳錯誤訊息
        echo json_encode(['status' => 'error', 'message' => '帳號或密碼錯誤']);
    }
} else {
    // 如果不是 POST 請求，返回錯誤訊息
    echo json_encode(['status' => 'error', 'message' => '不正確的請求方式']);
}
?>
