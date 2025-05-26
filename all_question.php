
<?php 
session_start();
include 'database_link.php';


// 取得所有問題及其提問者資訊
$sql = "SELECT p.post_id, p.title, p.content, p.created_at, p.tags,p.student_id
        FROM posts p
        ORDER BY p.post_id DESC";
$result = $conn->query($sql);
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);
?>


<?php
$conn->close();
?>
