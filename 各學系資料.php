<?php
// 資料庫設定
$pdo = new PDO("mysql:host=127.0.0.1;dbname=department_transfer", 'root', '');  // 根據實際資料庫配置修改
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 取得搜尋條件
$college = isset($_POST['college']) ? $_POST['college'] : '';
$grade = isset($_POST['grade']) ? $_POST['grade'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

// 構建 SQL 查詢
$query = "SELECT * FROM departments WHERE 1=1"; // 預設查詢所有部門資料

if ($college) {
    $query .= " AND faculty LIKE :college";  // 根據學院篩選
}
if ($grade) {
    $query .= " AND grade = :grade";  // 根據年級篩選（假設有年級欄位，根據需要調整）
}
if ($keyword) {
    $query .= " AND name LIKE :keyword";  // 根據關鍵字篩選
}

// 準備並執行查詢
$stmt = $pdo->prepare($query);

// 綁定參數
if ($college) $stmt->bindValue(':college', '%' . $college . '%');
if ($grade) $stmt->bindValue(':grade', $grade);
if ($keyword) $stmt->bindValue(':keyword', '%' . $keyword . '%');

$stmt->execute();

// 獲取查詢結果
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 顯示搜尋結果 -->
<?php if ($results): ?>
    <div>
        <h2>搜尋結果</h2>
        <?php foreach ($results as $department): ?>
            <div>
                <h3><?php echo htmlspecialchars($department['name']); ?></h3>
                <p><?php echo htmlspecialchars($department['faculty']); ?> - <?php echo htmlspecialchars($department['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>沒有符合條件的學系資料。</p>
<?php endif; ?>
