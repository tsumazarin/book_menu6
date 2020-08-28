<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['cus_login']['now'])==true){
    $login_name=$_SESSION['cus_login']['name'];
  }

  //選択された古本を取り出す
  $stmt=$db->prepare('SELECT *
    FROM
      mst_product
    WHERE
      code=?
  ');
  $stmt->execute(array($_REQUEST['procode']));
  $rec=$stmt->fetch();

  //カートを上書き
  if(isset($_SESSION['cart'])==true){
    $carts=$_SESSION['cart'];
    $number=$_SESSION['number'];
    $price=$_SESSION['cart_price'];
  }

  $carts[]=$rec['code'];
  $number[]=1;
  $price[]=$rec['price'];
  $_SESSION['cart']=$carts;
  $_SESSION['number']=$number;
  $_SESSION['cart_price']=$price;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ</title>
  </head>
  <body>
    <?php if($_SESSION['cus_login']['now']): ?>
      <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
      <a class="button logout" href="member_logout.php">ログアウト</a>
      <div class="clear"></div>
    <?php else: ?>
      <p>ゲストさん、ようこそ</p><br>
      <a class="button login" href="member_login.php">会員ログイン</a>
      <div class="clear"></div>
    <?php endif; ?>
    <div class="midashi-wrapper">
      <h2>カート追加</h2><br>
      <br>
      <p class="done">『<?php echo h($rec['name']); ?>』をカートに追加しました</p>
    </div>
    <br><br>
    <a class="button" href="shop_list.php">古本一覧に戻る</a>
  </body>
</html>
