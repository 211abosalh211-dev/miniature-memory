<?php
require 'config.php'; require_login(); $pdo=db();
if($_SERVER['REQUEST_METHOD']==='POST'){csrf_check();$st=$pdo->prepare("INSERT INTO expenses(title,amount) VALUES(?,?)");$st->execute([trim($_POST['title']),(float)$_POST['amount']]);flash('success','تم تسجيل المصروف.');redirect('expenses.php');}
$rows=$pdo->query("SELECT * FROM expenses ORDER BY id DESC LIMIT 200")->fetchAll();
?>
<?php include 'header.php'; ?><section class="page-head"><div><h1>المصروفات</h1><p>تسجيل المصروفات التشغيلية.</p></div></section>
<div class="panel"><form method="post" class="inline-form"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input name="title" placeholder="بيان المصروف" required><input type="number" step="0.01" name="amount" placeholder="المبلغ" required><button class="btn primary">إضافة</button></form></div>
<div class="panel table-wrap"><table><thead><tr><th>البيان</th><th>المبلغ</th><th>التاريخ</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=e($r['title'])?></td><td><?=money($r['amount'])?></td><td><?=e($r['created_at'])?></td></tr><?php endforeach; ?>
</tbody></table></div><?php include 'footer.php'; ?>