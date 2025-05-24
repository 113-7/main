<?php
session_start();
include 'database_link.php';

$transfer_id = $_SESSION['department_id'] ?? '';

// 查詢特定學系的所有申請資料及其審核狀態
$sql = "
    SELECT 
        a.application_id,
        a.student_id,
        u.username AS student_name,
        od.name AS odepartment_name,
        a.application_date,
        a.application_file,
        a.status
    FROM applications a
    JOIN users u ON a.student_id = u.user_id
    JOIN departments od ON u.department_id = od.department_id
    WHERE a.transfer_id = ?
    ORDER BY a.application_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $transfer_id);
$stmt->execute();
$result = $stmt->get_result();

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($applications, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
?>
