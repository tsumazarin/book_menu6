<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == true) {
    $login_name = $_SESSION['cus_login']['name'];
  }

  $carts = $_SESSION['carts'];

  $stmt = $db->prepare('SELECT *
    FROM
      mst_product
    WHERE
      code=?
  ');
  $stmt->execute(array($_REQUEST['procode']));
  $rec = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ</title>
  </head>
  <body>
    <?php if ($_SESSION['cus_login']['now']) : ?>
      <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
      <a class="button logout" href="member_logout.php">ログアウト</a>
      <div class="clear"></div>
    <?php else : ?>
      <p>ゲストさん、ようこそ</p><br>
      <a class="button login" href="member_login.php">会員ログイン</a>
      <div class="clear"></div>
    <?php endif; ?>
    <div class="midashi-wrapper">
      <h2>古本参照</h2>
    </div>
    <br>
    <?php if(in_array($rec['code'], $carts) == true) : ?>
      <p>カートに入っています</p>
    <?php else : ?>
      <a class="button" href="shop_cartin.php?procode=<?php echo h($rec['code']); ?>">
        カートに入れる
      </a>
      <div class="clear"></div>
    <?php endif; ?>
    <dl>
      <div class="left">
        <dt class="list">古本コード：<?php echo h($rec['code']); ?></dt>
        <br>
        <dt class="list">タイトル：『<?php echo h($rec['name']); ?>』</dt>
        <br>
        <dt class="list">価格：<?php echo h($rec['price']); ?>円</dt>
        <br>
      </div>
      <div class="right">
        <img src="../product/pro_picture/<?php echo h($rec['image']) ?>" alt="<?php echo h($rec['name']); ?>">
      </div>
      <div class="clear"></div>
    </dl>
    <br><br>
    <a class="button" href="shop_list.php">戻る</a>
  </body>
</html>
