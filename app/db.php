<?php
/**
 * Ket noi co so du lieu MySQL bang bien moi truong
 * Sinh vien: DTC245180088 - De 13: Website Portfolio
 */

// Doc cau hinh tu bien moi truong
$db_host = getenv('DB_HOST') ?: (getenv('MYSQL_HOST') ?: 'mysql_db');
$db_port = (int)(getenv('DB_PORT') ?: 3306);
$db_name = getenv('MYSQL_DATABASE') ?: (getenv('DB_NAME') ?: 'portfolio_db');
$db_user = getenv('MYSQL_USER') ?: (getenv('DB_USER') ?: 'portfolio_user');
$db_pass = getenv('MYSQL_PASSWORD') ?: (getenv('DB_PASSWORD') ?: 'PortfolioUser@SecretPass2026!$');

// Thiet lap bao loi
mysqli_report(MYSQLI_REPORT_OFF);

// Ket noi toi MySQL
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

// Kiem tra loi ket noi
if ($conn->connect_error) {
    // Luu thong tin loi de cac trang co the hien thi thong bao than thien
    $db_connected = false;
    $db_error_message = $conn->connect_error;
} else {
    $db_connected = true;
    $db_error_message = null;
    $conn->set_charset("utf8mb4");
}

/**
 * Ham lay doi tuong ket noi MySQL
 * @return mysqli|null
 */
function getDbConnection() {
    global $conn, $db_connected;
    return $db_connected ? $conn : null;
}
?>
