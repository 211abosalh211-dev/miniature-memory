<?php
require 'config.php'; require_login(); $pdo=db();
if($_SERVER['REQUEST_METHOD']==='POST'){ csrf_check();
 try{
  $st=$pdo->prepare("INSERT INTO products(name,barcode,buy_price,sell_price,stock,min_stock) VALUES(?,?,?,?,?,?)");
  $st->execute([trim($_POST['name']),trim($_POST['barcode'])?:null,(float)$_POST['buy_price'],(float)$_POST['sell_price'],(int)$_POST['stock'],(int)$_POST['min_stock']]);
  flash('success','تمت إضافة المنتج.'); redirect('products.php');
 }catch(PDOException $e){ flash('danger','تعذر إضافة المنتج: قد يكون الباركود مستخدمًا.'); redirect('products.php');}
}
$rows=$pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<?php include 'header.php'; ?><section class="page-head"><div><h1>المنتجات والمخزون</h1><p>إدارة الأسعار والكميات والتنبيهات.</p></div></section>
<div class="panel"><h3>إضافة منتج</h3><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<div><label>اسم المنتج</label><input name="name" required></div><div><label>الباركود</label><input name="barcode"></div>
<div><label>سعر الشراء</label><input type="number" step="0.01" name="buy_price" value="0"></div><div><label>سعر البيع</label><input type="number" step="0.01" name="sell_price" value="0"></div>
<div><label>المخزون</label><input type="number" name="stock" value="0"></div><div><label>حد التنبيه</label><input type="number" name="min_stock" value="0"></div>
<button class="btn primary">إضافة المنتج</button></form></div>
<div class="panel"><div class="table-tools"><input id="tableSearch" placeholder="بحث..."></div>
<div class="table-wrap"><table id="dataTable"><thead><tr><th>المنتج</th><th>الباركود</th><th>شراء</th><th>بيع</th><th>المخزون</th><th>الحالة</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=e($r['name'])?></td><td><?=e($r['barcode'])?></td><td><?=money($r['buy_price'])?></td><td><?=money($r['sell_price'])?></td><td><?=e($r['stock'])?></td><td><?=($r['stock']<=$r['min_stock'])?'<span class="badge warn">منخفض</span>':'<span class="badge ok">متوفر</span>'?></td></tr><?php endforeach; ?>
</tbody></table></div></div><?php include 'footer.php'; ?>