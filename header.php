<?php
$cur=basename($_SERVER['PHP_SELF']);
function nav_active($x){ global $cur; return $cur===$x?'active':''; }
?>
<!doctype html><html lang="ar" dir="rtl"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ShopManager PRO</title><link rel="stylesheet" href="assets/style.css">
<script defer src="assets/app.js"></script></head><body>
<div class="layout"><aside class="sidebar">
<div class="logo">ShopManager <b>PRO</b></div>
<nav>
<a class="<?=nav_active('index.php')?>" href="index.php">🏠 لوحة التحكم</a>
<a class="<?=nav_active('pos.php')?>" href="pos.php">🛒 نقطة البيع</a>
<a class="<?=nav_active('products.php')?>" href="products.php">📦 المنتجات والمخزون</a>
<a class="<?=nav_active('customers.php')?>" href="customers.php">👥 العملاء</a>
<a class="<?=nav_active('expenses.php')?>" href="expenses.php">💸 المصروفات</a>
<a class="<?=nav_active('reports.php')?>" href="reports.php">📊 التقارير</a>
<?php if(is_admin()): ?><a class="<?=nav_active('users.php')?>" href="users.php">🔐 المستخدمون</a><?php endif; ?>
</nav><div class="side-bottom"><span><?=e($_SESSION['user']['name'])?></span><a href="logout.php">تسجيل الخروج</a></div>
</aside><main class="content">
<?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']); ?><div class="alert <?=$f[0]?>"><?=e($f[1])?></div><?php endif; ?>