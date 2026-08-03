<?php
require 'config.php'; require_login(); $pdo=db();
$from=$_GET['from']??date('Y-m-01');$to=$_GET['to']??date('Y-m-d');
$st=$pdo->prepare("SELECT COALESCE(SUM(total),0) sales,COALESCE(SUM(profit),0) profit,COUNT(*) cnt FROM sales WHERE DATE(created_at) BETWEEN ? AND ?");
$st->execute([$from,$to]);$sum=$st->fetch();
$st=$pdo->prepare("SELECT DATE(created_at) day,SUM(total) sales,SUM(profit) profit,COUNT(*) cnt FROM sales WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY DATE(created_at) ORDER BY day DESC");
$st->execute([$from,$to]);$rows=$st->fetchAll();
?>
<?php include 'header.php'; ?><section class="page-head"><div><h1>التقارير</h1><p>ملخص المبيعات والأرباح حسب الفترة.</p></div></section>
<div class="panel"><form class="inline-form"><input type="date" name="from" value="<?=e($from)?>"><input type="date" name="to" value="<?=e($to)?>"><button class="btn primary">عرض</button></form></div>
<div class="cards"><div class="card"><span>المبيعات</span><strong><?=money($sum['sales'])?></strong></div><div class="card"><span>الأرباح</span><strong><?=money($sum['profit'])?></strong></div><div class="card"><span>عدد الفواتير</span><strong><?=$sum['cnt']?></strong></div></div>
<div class="panel table-wrap"><table><thead><tr><th>اليوم</th><th>المبيعات</th><th>الأرباح</th><th>الفواتير</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?=e($r['day'])?></td><td><?=money($r['sales'])?></td><td><?=money($r['profit'])?></td><td><?=$r['cnt']?></td></tr><?php endforeach; ?></tbody></table></div>
<?php include 'footer.php'; ?>