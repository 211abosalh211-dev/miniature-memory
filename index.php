<?php
require 'config.php'; require_login();
$pdo=db();
$today=date('Y-m-d');
$stats=$pdo->prepare("SELECT COALESCE(SUM(total),0) sales, COALESCE(SUM(profit),0) profit, COUNT(*) cnt FROM sales WHERE DATE(created_at)=?");
$stats->execute([$today]); $s=$stats->fetch();
$products=(int)$pdo->query("SELECT COUNT(*) c FROM products")->fetch()['c'];
$low=(int)$pdo->query("SELECT COUNT(*) c FROM products WHERE stock<=min_stock")->fetch()['c'];
$expenses=(float)$pdo->query("SELECT COALESCE(SUM(amount),0) a FROM expenses WHERE DATE(created_at)=CURDATE()")->fetch()['a'];
?>
<?php include 'header.php'; ?>
<section class="page-head"><div><h1>لوحة التحكم</h1><p>مرحبًا <?=e($_SESSION['user']['name'])?>، إليك ملخص اليوم.</p></div><a class="btn primary" href="pos.php">+ عملية بيع</a></section>
<div class="cards">
<div class="card"><span>مبيعات اليوم</span><strong><?=money($s['sales'])?></strong></div>
<div class="card"><span>أرباح اليوم</span><strong><?=money($s['profit'])?></strong></div>
<div class="card"><span>مصروفات اليوم</span><strong><?=money($expenses)?></strong></div>
<div class="card"><span>تنبيه مخزون</span><strong><?=$low?></strong></div>
</div>
<div class="grid-2">
<div class="panel"><h3>اختصارات</h3><div class="quick">
<a href="products.php">📦 المنتجات</a><a href="customers.php">👥 العملاء</a><a href="pos.php">🛒 نقطة البيع</a><a href="reports.php">📊 التقارير</a>
</div></div>
<div class="panel"><h3>حالة النظام</h3><p>عدد المنتجات: <b><?=$products?></b></p><p>مبيعات اليوم: <b><?=e($s['cnt'])?></b> عملية</p></div>
</div>
<?php include 'footer.php'; ?>