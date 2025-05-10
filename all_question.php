
<?php //前端是chatgpt得 我不確定怎麼搞
session_start();
require_once 'database_link.php';

// 確認使用者為學生
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    http_response_code(403);
    echo "無權限存取此功能。";
    exit;
}

// 取得所有問題及其提問者資訊
$sql = "SELECT q.question_id, q.title, q.content, q.created_at, s.name AS student_name
        FROM questions q
        JOIN students s ON q.student_id = s.student_id
        ORDER BY q.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>討論區 - 所有問題與回答</title>
</head>
<body>
    <h1>討論區 - 所有問題與回答</h1>

    <?php while ($question = $result->fetch_assoc()): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
            <h2><?php echo htmlspecialchars($question['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($question['content'])); ?></p>
            <p>提問者：<?php echo htmlspecialchars($question['student_name']); ?> | 發問時間：<?php echo $question['created_at']; ?></p>

            <?php
            // 取得該問題的所有回答
            $stmt = $conn->prepare("SELECT a.content, a.created_at, s.name AS student_name
                                    FROM answers a
                                    JOIN students s ON a.student_id = s.student_id
                                    WHERE a.question_id = ?
                                    ORDER BY a.created_at ASC");
            $stmt->bind_param("i", $question['question_id']);
            $stmt->execute();
            $answers = $stmt->get_result();
            ?>

            <h3>回答：</h3>
            <?php if ($answers->num_rows > 0): ?>
                <?php while ($answer = $answers->fetch_assoc()): ?>
                    <div style="border: 1px solid #eee; padding: 10px; margin-bottom: 10px;">
                        <p><?php echo nl2br(htmlspecialchars($answer['content'])); ?></p>
                        <p>回答者：<?php echo htmlspecialchars($answer['student_name']); ?> | 回答時間：<?php echo $answer['created_at']; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>目前尚無回答。</p>
            <?php endif; ?>

            <?php $stmt->close(); ?>
        </div>
    <?php endwhile; ?>

</body>
</html>

<?php
$conn->close();
?>
