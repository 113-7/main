<?php
include('database_link.php');
//前端用include這裡要確定來源，雖然上面有全指定
header("Access-Control-Allow-Origin: http://localhost:8080");

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    echo json_encode([
        "loggedIn" => true,
        "userId" => $_SESSION['user_id'],
        "role" => $_SESSION['role'],
    ]);
} else {
    echo json_encode([
        "loggedIn" => false,
    ]);
}