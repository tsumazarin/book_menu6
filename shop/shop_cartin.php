<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == true) {
    $login_name = $_SESSION['cus_login']['name'];
  }

  //選択された古本を取り出す
  $stmt = $db->prepare('SELECT *
    FROM
      mst_product
    WHERE
      code=?
  ');
  $stmt->execute(array($_REQUEST['procode']));
  $rec = $stmt->fetch();

  //カートを上書き
  if (isset($_SESSION['carts']) == true) {
    $carts = $_SESSION['carts'];
    $number = $_SESSION['number'];
    $selected_price = $_SESSION['cart_price'];
  }

  $carts[] = $rec['code'];
  $number[] = 1;
  $selected_price[] = $rec['price'];
  $_SESSION['carts'] = $carts;
  $_SESSION['number'] = $number;
  $_SESSION['cart_price'] = $selected_price;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section><br>
      <?php if ($_SESSION['cus_login']['now']) : ?>
        <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
        <a class="button white" href="member_logout.php">ログアウト</a>
      <?php else : ?>
        <p>ゲストさん、ようこそ</p><br>
        <a class="button white" href="member_login.php">会員ログイン</a>
      <?php endif; ?>
    </header>
    <main>
      <h2 class="heading">カート追加</h2><br>
      <br>
      <p>『<?php echo h($rec['name']); ?>』をカートに追加しました</p>
      <br><br>
      <a class="button black" href="shop_list.php">古本一覧に戻る</a>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
