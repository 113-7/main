<?php
session_start();
include 'database_link.php';

$student_id = $_SESSION['user_id'];

// 查詢學生申請資料跟其審核狀態
$sql = "
    SELECT 
  a.application_id,
  a.student_id,
  d.name AS department_name,
  d.faculty AS department_faculty,
  a.application_date,
  a.application_file,
  a.status
FROM applications a
JOIN departments d ON a.transfer_id = d.department_id
WHERE a.student_id = ?
ORDER BY a.application_date;
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
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
