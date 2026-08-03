<?php
// ShopManager PRO - Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shopmanager');
define('DB_USER', 'root');
define('DB_PASS', '');

session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
            DB_USER, DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
    return $pdo;
}
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function redirect($url='index.php'): never { header("Location: $url"); exit; }
function flash($type, $msg): void { $_SESSION['flash'] = [$type, $msg]; }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('طلب غير صالح.');
    }
}
function logged_in(): bool { return !empty($_SESSION['user']); }
function require_login(): void { if (!logged_in()) redirect('login.php'); }
function is_admin(): bool { return ($_SESSION['user']['role'] ?? '') === 'admin'; }
function money($n): string { return number_format((float)$n, 2); }
?>