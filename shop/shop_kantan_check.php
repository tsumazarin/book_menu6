<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == false) {
    header('Location: shop_cartlook.php');
    exit();
  }

  $login_name = $_SESSION['cus_login']['name'];
  $login_code = $_SESSION['cus_login']['code'];
  $stmt=$db->prepare('SELECT
      dm.name,
      dm.email,
      dm.postal,
      dm.address,
      dm.tel
    FROM
      dat_member dm
    WHERE
      code=?
  ');
  $stmt->execute(array($login_code));
  $rec2 = $stmt->fetch();

  $name = $rec2['name'];
  $email = $rec2['email'];
  $postal = $rec2['postal'];
  $address = $rec2['address'];
  $tel = $rec2['tel'];
  $_SESSION['cus']['name'] = $rec2['name'];
  $_SESSION['cus']['email'] = $rec2['email'];
  $_SESSION['cus']['postal'] = $rec2['postal'];
  $_SESSION['cus']['address'] = $rec2['address'];
  $_SESSION['cus']['tel'] = $rec2['tel'];


  $carts = $_SESSION['cart'];
  $number = $_SESSION['number'];
  $price = $_SESSION['cart_price'];
  $max = count($carts);

  if (isset($_POST['done']) == true || isset($_POST['card']) == true) {
    //占有ロック
    $stmt=$db->prepare('LOCK TABLES
      dat_sales WRITE,
      dat_sales_product WRITE
    ');
    $stmt->execute();

    //dat_salesにデータを入れる
    $stmt = $db->prepare('INSERT INTO
        dat_sales(
          code_member,
          name,
          email,
          postal,
          address,
          tel
        )
      VALUES
        (?,?,?,?,?,?)
    ');
    $stmt->execute(array(
      $login_code,
      $name,
      $email,
      $postal,
      $address,
      $tel
    ));

    //購入履歴IDの最後を取得
    $stmt = $db->prepare('SELECT LAST_INSERT_ID()');
    $stmt->execute();
    $rec = $stmt->fetch();
    $lastcode = $rec['LAST_INSERT_ID()'];

    //dat_sales_productにデータを入れる
    for ($i = 0; $i < $max; $i++) {
      $stmt = $db->prepare('INSERT INTO
          dat_sales_product(
            code_sales,
            code_product,
            price,quantity
          )
        VALUES
          (?,?,?,?)
      ');
      $stmt->execute(array(
        $lastcode,
        $carts[$i],
        $price[$i],
        $number[$i]
      ));
    }

    //ロック解除
    $stmt = $db->prepare('UNLOCK TABLES');
    $stmt->execute();

    $db = null;

    //代引きの場合
    if (isset($_POST['done']) == true) {
      $_SESSION['pay'] = 'cash';
      header('Location: shop_form_done.php');
      exit();
    }

    //カード払いの場合
    if (isset($_POST['card']) == true) {
      $_SESSION['pay'] = 'card';
      header('Location: shop_card.php');
      exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
    <h2>お客様情報</h2>
    <form action="" method="post">
      <dl>
        <dt>お名前：<?php echo h($rec2['name']); ?></dt>
        <dd></dd>
        <br>
        <dt>メールアドレス：<?php echo h($rec2['email']); ?></dt>
        <dd></dd>
        <br>
        <dt>郵便番号：<?php echo h($rec2['postal']); ?></dt>
        <dd></dd>
        <br>
        <dt>住所：<?php echo h($rec2['address']); ?></dt>
        <dd></dd>
        <br>
        <dt>電話番号：<?php echo h($rec2['tel']); ?></dt>
        <dd></dd>
      </dl>
      <div class="cartlook">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="注文"> |
        <input class="button" type="submit" name="card" value="カード払い">
      </div>
    </form>
    <footer></footer>
  </body>
</html>
