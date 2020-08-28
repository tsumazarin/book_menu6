<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $name=$_SESSION['cus']['name'];
  $email=$_SESSION['cus']['email'];
  $postal=$_SESSION['cus']['postal'];
  $address=$_SESSION['cus']['address'];
  $tel=$_SESSION['cus']['tel'];
  $order=$_SESSION['cus']['order'];
  $pass=$_SESSION['cus']['pass'];
  $gender=$_SESSION['cus']['gender'];
  $birth=$_SESSION['cus']['birth'];

  $carts=$_SESSION['cart'];
  $number=$_SESSION['number'];
  $price=$_SESSION['cart_price'];
  $max=count($carts);

  if(isset($_POST['card'])==true || isset($_POST['done'])==true){
    //占有ロック開始
    $stmt=$db->prepare('LOCK TABLES
      dat_sales WRITE,
      dat_sales_product WRITE,
      dat_member WRITE
    ');
    $stmt->execute();

    //会員登録情報をデータベス（dat_member）へ
    if($order=='order_register'){
      $stmt=$db->prepare('INSERT INTO
        dat_member(
          password,
          name,
          email,
          postal,
          address,
          tel,
          gender,
          birth
        )
        VALUES
          (?,?,?,?,?,?,?,?)
      ');
      $stmt->execute(array(
        $pass,
        $name,
        $email,
        $postal,
        $address,
        $tel,
        $gender,
        $birth
      ));

      //購入履歴IDの最後を取り出す
      $last_member_code=0;
      $stmt=$db->prepare('SELECT LAST_INSERT_ID()');
      $stmt->execute();
      $rec=$stmt->fetch();
      $last_member_code=$rec['LAST_INSERT_ID()'];
    }

    //dat_salesにデータを入れる
    $stmt=$db->prepare('INSERT INTO
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
      $last_member_code,
      $name,
      $email,
      $postal,
      $address,
      $tel
    ));

    //dat_sales_productにデータを入れる
    $stmt=$db->prepare('SELECT LAST_INSERT_ID()');
    $stmt->execute();
    $rec=$stmt->fetch();
    $last_code=$rec['LAST_INSERT_ID()'];

    for($i=0; $i<$max; $i++){
      $stmt=$db->prepare('INSERT INTO dat_sales_product(
          code_sales,
          code_product,
          price,
          quantity
        )
        VALUES
          (?,?,?,?)
      ');

      $stmt->execute(array(
        $last_code,
        $carts[$i],
        $price[$i],
        $number[$i]
      ));
    }

    //ロック解除
    $stmt=$db->prepare('UNLOCK TABLES');
    $stmt->execute();

    $db=null;

    //代引きの場合
    if(isset($_POST['done'])==true){
      $_SESSION['pay']='cash';
      header('Location: shop_form_done.php');
      exit();
    }

    //カード払いの場合
    if(isset($_POST['card'])==true){
      $_SESSION['pay']='card';
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
    <title>古本の主人</title>
  </head>
  <body>
    <h2>お客様情報</h2>
    <form action="" method="post">
      <dl>
        <dt>お名前：<?php echo h($name); ?></dt>
        <dd></dd>
        <br>
        <dt>メールアドレス：<?php echo h($email); ?></dt>
        <dd></dd>
        <br>
        <dt>郵便番号：<?php echo h($postal); ?></dt>
        <dd></dd>
        <br>
        <dt>住所：<?php echo h($address); ?></dt>
        <dd></dd>
        <br>
        <dt>電話番号：<?php echo h($tel); ?></dt>
        <dd></dd>
        <?php if(isset($_SESSION['cus']['pass'])==true): ?>
          <br>
          <dt>パスワード：【表示されません】</dt>
          <dd></dd>
          <br>
          <dt>
            性別：
            <?php if($gender==1): ?>
              男性
            <?php else: ?>
              女性
            <?php endif; ?>
          </dt>
          <dd></dd>
          <br>
          <dt>生まれ年：<?php echo h($birth); ?>年代</dt>
          <dd></dd>
        <?php endif; ?>
      </dl>
      <div class="cartlook">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="代引き"> |
        <input class="button" type="submit" name="card" value="カード払い">
      </div>
    </form>
    <footer></footer>
  </body>
</html>
