<?php
require 'config.php'; require_login(); $pdo=db();
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $items=json_decode($_POST['cart']??'[]',true) ?: [];
 $customer=(int)($_POST['customer_id']??0); $paid=(float)($_POST['paid']??0);
 if(!$items){flash('danger','السلة فارغة.');redirect('pos.php');}
 try{
  $pdo->beginTransaction(); $total=0;$profit=0;$clean=[];
  foreach($items as $it){
   $st=$pdo->prepare("SELECT * FROM products WHERE id=? FOR UPDATE");$st->execute([(int)$it['id']]);$p=$st->fetch();
   $qty=max(1,(int)$it['qty']); if(!$p || $p['stock']<$qty) throw new Exception('المخزون غير كافٍ لأحد المنتجات.');
   $total += $p['sell_price']*$qty; $profit += ($p['sell_price']-$p['buy_price'])*$qty;
   $clean[]=['p'=>$p,'qty'=>$qty];
  }
  if($paid>$total)$paid=$total; $debt=$total-$paid;
  $st=$pdo->prepare("INSERT INTO sales(customer_id,user_id,total,profit,paid,debt) VALUES(?,?,?,?,?,?)");
  $st->execute([$customer?:null,$_SESSION['user']['id'],$total,$profit,$paid,$debt]);$sid=$pdo->lastInsertId();
  foreach($clean as $x){$p=$x['p'];$q=$x['qty'];$pdo->prepare("INSERT INTO sale_items(sale_id,product_id,qty,price,cost) VALUES(?,?,?,?,?)")->execute([$sid,$p['id'],$q,$p['sell_price'],$p['buy_price']]);$pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?")->execute([$q,$p['id']]);}
  if($customer && $debt>0)$pdo->prepare("UPDATE customers SET debt=debt+? WHERE id=?")->execute([$debt,$customer]);
  $pdo->commit(); flash('success',"تمت عملية البيع رقم #$sid بإجمالي ".money($total)); redirect('pos.php');
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();flash('danger',$e->getMessage());redirect('pos.php');}
}
$products=$pdo->query("SELECT id,name,barcode,sell_price,stock FROM products WHERE stock>0 ORDER BY name")->fetchAll();
$customers=$pdo->query("SELECT id,name,debt FROM customers ORDER BY name")->fetchAll();
?>
<?php include 'header.php'; ?><section class="page-head"><div><h1>نقطة البيع POS</h1><p>اختر المنتجات ثم نفذ الفاتورة.</p></div></section>
<div class="pos-layout"><div class="panel"><h3>المنتجات</h3><input id="productSearch" placeholder="ابحث بالاسم أو الباركود..."><div id="productList" class="product-list">
<?php foreach($products as $p): ?><button class="product" data-id="<?=$p['id']?>" data-name="<?=e($p['name'])?>" data-price="<?=$p['sell_price']?>" data-stock="<?=$p['stock']?>"><b><?=e($p['name'])?></b><span><?=money($p['sell_price'])?></span><small>مخزون: <?=$p['stock']?></small></button><?php endforeach; ?>
</div></div>
<div class="panel"><h3>السلة</h3><div id="cart" class="cart"></div><div class="total-box">الإجمالي <strong id="total">0.00</strong></div>
<form method="post" id="saleForm"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="cart" id="cartInput">
<label>العميل</label><select name="customer_id"><option value="0">عميل نقدي</option><?php foreach($customers as $c): ?><option value="<?=$c['id']?>"><?=e($c['name'])?> — دين <?=money($c['debt'])?></option><?php endforeach; ?></select>
<label>المدفوع</label><input type="number" step="0.01" name="paid" id="paid" value="0"><button class="btn primary full" type="submit">إتمام البيع</button></form></div></div>
<?php include 'footer.php'; ?>