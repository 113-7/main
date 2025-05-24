<?php
include 'database_link.php';

// 接收 POST 資料
$input = json_decode(file_get_contents("php://input"), true);

$application_id = $input['application_id'] ?? '';
$status = $input['status'] ?? '';


//因為資料庫一直沒更新，用下面兩行查看變數類型
//var_dump($application_id);
//var_dump($status);

// 更新 application_status
$stmt = $conn->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
$stmt->bind_param("si", $status, $application_id);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => '申請狀態更新成功。',
            'rows_affected' => $stmt->affected_rows
        ]);
    } else {
        echo json_encode([
            'status' => 'no_change',
            'message' => '資料無變更（可能新值與舊值相同或 application_id 不存在）',
            'rows_affected' => $stmt->affected_rows
        ]);
    }
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => '申請狀態更新失敗：' . $stmt->error
    ]);
}
$conn->close();
?>
