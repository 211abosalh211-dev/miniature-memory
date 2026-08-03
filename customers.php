<?php
require 'config.php'; require_login(); $pdo=db();
if($_SERVER['REQUEST_METHOD']==='POST'){csrf_check(); $st=$pdo->prepare("INSERT INTO customers(name,phone,address,debt) VALUES(?,?,?,?)");$st->execute([trim($_POST['name']),trim($_POST['phone']),trim($_POST['address']),0]);flash('success','تمت إضافة العميل.');redirect('customers.php');}
$rows=$pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();
?>
<?php include 'header.php'; ?><section class="page-head"><div><h1>العملاء والديون</h1><p>حفظ بيانات العملاء ومتابعة المستحقات.</p></div></section>
<div class="panel"><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<div><label>اسم العميل</label><input name="name" required></div><div><label>الهاتف</label><input name="phone"></div><div><label>العنوان</label><input name="address"></div>
<button class="btn primary">إضافة عميل</button></form></div>
<div class="panel table-wrap"><table><thead><tr><th>الاسم</th><th>الهاتف</th><th>العنوان</th><th>الدين</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=e($r['name'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['address'])?></td><td><?=money($r['debt'])?></td></tr><?php endforeach; ?>
</tbody></table></div><?php include 'footer.php'; ?>