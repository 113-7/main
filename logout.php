<?php
include('database_link.php');
session_start();  // 開啟 session

// 清除所有 session 變數
session_unset();  // 清除 $_SESSION 變數

// 銷毀 session
session_destroy();  // 銷毀 session

// 回應登出成功
echo json_encode(['status' => 'success', 'message' => '登出成功']);

// 如果需要，重定向回登入頁面
// header('Location: login.php'); // 取消註解這行來重定向回登入頁
?>
