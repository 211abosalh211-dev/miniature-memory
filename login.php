<?php
require 'config.php';
if (logged_in()) redirect();

$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check();
    $st=db()->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $st->execute([trim($_POST['username'] ?? '')]);
    $u=$st->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'username'=>$u['username'],'role'=>$u['role']];
        redirect();
    }
    $error='اسم المستخدم أو كلمة المرور غير صحيحة.';
}
?>
<!doctype html><html lang="ar" dir="rtl"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>تسجيل الدخول - ShopManager PRO</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="login-page"><div class="login-card">
<div class="brand">ShopManager <b>PRO</b></div><p class="muted">نظام إدارة المبيعات والمخزون</p>
<?php if($error): ?><div class="alert danger"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<label>اسم المستخدم</label><input name="username" required autocomplete="username">
<label>كلمة المرور</label><input type="password" name="password" required autocomplete="current-password">
<button class="btn primary full">دخول</button></form>
<div class="hint">التجربة: admin / password</div></div></body></html>