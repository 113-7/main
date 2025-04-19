<?php
// 模擬 POST 請求
$_POST['username'] = 'testuser1'; // 輸入帳號
$_POST['password'] = 'password123'; // 輸入密碼

// 引入 login.php 進行測試
include('login.php');
?>
