<?php
session_start();
include 'database_link.php';


// 接收 POST 資料和session
$student_id = $_POST['user_id'];
$transfer_id = $_POST['transfer_id'];
$status = '審核中';

//我要做防呆確認有名額才可以繳交申請
function getGradeFromId($student_id) {
    if (strlen($student_id) < 3) return null;
    $admissionYear = intval("1" . substr($student_id, 1, 2));
    $currentYear = intval(date("Y")) - 1911; // 民國年
    return $currentYear - $admissionYear;
}
$grade = getGradeFromId($student_id);

// 查詢該系的名額設定
$sql = "SELECT second_year_quota, third_year_quota, fourth_year_quota FROM departments WHERE department_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "fail", "message" => "SQL準備失敗: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $transfer_id);
$stmt->execute();
$result = $stmt->get_result();
$quotaRow = $result->fetch_assoc();

if (!$quotaRow) {
    echo json_encode(["status" => "fail", "message" => "找不到該系所資料"]);
    exit;
}

switch ($grade) {
    case 2:
        $quota = $quotaRow['second_year_quota'];
        break;
    case 3:
        $quota = $quotaRow['third_year_quota'];
        break;
    case 4:
        $quota = $quotaRow['fourth_year_quota'];
        break;
    default:
        echo json_encode(["status" => "fail", "message" => "僅限二～四年級轉系"]);
        exit;
}

if ($quota <= 0) {
    echo json_encode(["status" => "fail", "message" => "該系所未開放您的年級申請"]);
    exit;
}

// 檢查是否有檔案上傳
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => '請上傳申請文件。'
    ]);
    exit;
}

// 設定儲存路徑（確保這個資料夾存在 & 有寫入權限）
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 組合檔名（避免重複）
$original_name = basename($_FILES['file']['name']);
$ext = pathinfo($original_name, PATHINFO_EXTENSION);
$custom_file_name = $student_id . '_' . $transfer_id . '.' . $ext;
$destination = $upload_dir . $custom_file_name;

// 移動檔案
if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => '檔案上傳失敗。'
    ]);
    exit;
}



// 先查詢是否已有申請資料
$sql_check = "SELECT application_id FROM applications WHERE student_id = ? AND transfer_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("si", $student_id, $transfer_id);
$stmt_check->execute();
$stmt_check->bind_result($application_id);
$exists = $stmt_check->fetch();
$stmt_check->close();

if ($exists) {
    // 已存在，使用 UPDATE
    $sql_update = "UPDATE applications SET status = ?, application_file = ? WHERE application_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $status, $custom_file_name, $application_id);
    if ($stmt_update->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => '您的申請已成功更新。'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => '申請更新失敗。'
        ]);
    }
    $stmt_update->close();
} else {
    // 沒有資料，使用 INSERT
    $stmt = $conn->prepare("INSERT INTO applications (student_id, transfer_id, status, application_file) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $student_id, $transfer_id, $status, $custom_file_name);
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => '您的申請已成功提交。'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => '申請提交失敗。'
        ]);
    }
    $stmt->close();
}

$conn->close();
?>
