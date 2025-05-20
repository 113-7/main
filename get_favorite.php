<?php
//找出我的所有收藏學系
include('database_link.php');
session_start();

$student_id = $_SESSION['user_id'];

// 查詢收藏的學系資訊
$sql = "
    SELECT d.department_id,
        d.name,
        d.faculty,
        d.second_year_quota,
        d.third_year_quota,
        d.fourth_year_quota,
        d.brief_description,
        d.written_exam_weight, 
        d.interview_weight, 
        d.review_weight, 
        d.additional_notes
    FROM favorite_departments f
    JOIN departments d ON f.department_id = d.department_id
    WHERE f.student_id = ?
    ORDER BY d.name ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$favorites = [];
while ($row = $result->fetch_assoc()) {
    $favorites[] = $row;
}

header('Content-Type: application/json');
echo json_encode($favorites, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
