<?php
include('database_link.php');
session_start();

// 確保用戶已登入
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  // 取得用戶的 user_id

    // 連接資料庫
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=department_transfer", 'root', ''); // 根據你的資料庫設置進行修改
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查詢用戶角色
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch();

    // 確認用戶是否為 admin（學系負責人）
    if ($user['role'] == 'admin') {
        // 用戶是學系負責人，繼續處理轉系資料
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true); // true 表示將 JSON 轉換為陣列

            $department_id = $data['department_id'];
            $second_year_quota = $data['second_year_quota'];
            $third_year_quota = $data['third_year_quota'];
            $fourth_year_quota = $data['fourth_year_quota'];
            $brief_description = $data['brief_description'];
            $written_exam_weight = $data['written_exam_weight'];
            $interview_weight = $data['interview_weight'];
            $review_weight = $data['review_weight'];
            $additional_notes = $data['additional_notes'];

            // 查詢該學系的轉系資料
            $stmt = $pdo->prepare("SELECT * FROM departments WHERE department_id = :department_id");
            $stmt->execute(['department_id' => $department_id]);
            $change = $stmt->fetch();

            // 如果用戶已經有轉系資料，更新資料
            if ($change) {
                $update_stmt = $pdo->prepare("
                    UPDATE departments
                    SET 
                        second_year_quota = :second_year_quota,
                        third_year_quota = :third_year_quota,
                        fourth_year_quota = :fourth_year_quota,
                        brief_description = :brief_description,
                        written_exam_weight = :written_exam_weight,
                        interview_weight = :interview_weight,
                        review_weight = :review_weight,
                        additional_notes = :additional_notes
                    WHERE department_id = :department_id
                ");
                $update_stmt->execute([
                    'second_year_quota' => $second_year_quota,
                    'third_year_quota' => $third_year_quota,
                    'fourth_year_quota' => $fourth_year_quota,
                    'brief_description' => $brief_description,
                    'written_exam_weight' => $written_exam_weight,
                    'interview_weight' => $interview_weight,
                    'review_weight' => $review_weight,
                    'additional_notes' => $additional_notes,
                    'department_id' => $department_id
                ]);

                echo json_encode(['status' => 'success', 'message' => '轉系資料已更新']);
            } else {
                // 如果用戶沒有轉系資料，則插入新的資料
                $insert_stmt = $pdo->prepare("
                    INSERT INTO departments 
                        (department_id, second_year_quota, third_year_quota, fourth_year_quota, brief_description, 
                        written_exam_weight, interview_weight, review_weight, additional_notes, status)
                    VALUES 
                        (:department_id, :second_year_quota, :third_year_quota, :fourth_year_quota, :brief_description, 
                        :written_exam_weight, :interview_weight, :review_weight, :additional_notes, 'Pending')
                ");
                $insert_stmt->execute([
                    'department_id' => $department_id,
                    'second_year_quota' => $second_year_quota,
                    'third_year_quota' => $third_year_quota,
                    'fourth_year_quota' => $fourth_year_quota,
                    'brief_description' => $brief_description,
                    'written_exam_weight' => $written_exam_weight,
                    'interview_weight' => $interview_weight,
                    'review_weight' => $review_weight,
                    'additional_notes' => $additional_notes
                ]);

                echo json_encode(['status' => 'success', 'message' => '轉系資料已新增']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => '請使用 POST 請求']);
        }
    } else {
        // 用戶不是 admin
        echo json_encode(['status' => 'error', 'message' => '您沒有權限修改該學系的資料']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '未登入']);
}
