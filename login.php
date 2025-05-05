<?php
// 允許跨域請求
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

session_start();

// 資料庫連線設定
// 資料庫連線設定
$pdo = new PDO("mysql:host=127.0.0.1;dbname=department_transfer", 'root', ''); // 根據你的設定修改
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 使用 $_POST 直接從表單中取得資料
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    // echo 'user_id: ' . $user_id . '<br>';
    // echo 'password: ' . $password . '<br>';

    // 查詢資料庫
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch();

    // header('Content-type: application/json');

    if ($user && $password == $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['department_id'] = $user['department_id'];

        echo json_encode(['status' => 'success', 'message' => '登入成功']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '帳號或密碼錯誤']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '不正確的請求方式']);
}

// 確認是否為 POST 請求
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // 從前端 Vue 傳遞的 JSON 中獲取帳號與密碼
//     $user_id = $data['user_id'];
//     $password = $data['password'];

//     // 查詢資料庫中的用戶資料
//     $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
//     $stmt->execute(['user_id' => $user_id]);
//     $user = $stmt->fetch();

//     // 檢查是否找到了該用戶並且密碼驗證成功
//     if ($user && $password==$user['password']/*password_verify($password, $user['password'])*/) {
//         // 登入成功，儲存用戶資料到 session
//         $_SESSION['user_id'] = $user['user_id'];
//         $_SESSION['name'] = $user['name'];
//         $_SESSION['role'] = $user['role'];
//         $_SESSION['department_id'] = $user['department_id'];
        
//         // 回傳登入成功訊息
//         echo json_encode(['status' => 'success', 'message' => '登入成功']);
//     } else {
//         // 登入失敗，回傳錯誤訊息
//         echo json_encode(['status' => 'error', 'message' => '帳號或密碼錯誤']);
//     }
// } else {
//     // 如果不是 POST 請求，返回錯誤訊息
//     echo json_encode(['status' => 'error', 'message' => '不正確的請求方式']);
// }
?>
