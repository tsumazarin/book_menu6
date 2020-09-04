<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == true) {
    $login_name = $_SESSION['cus_login']['name'];
  }

  //古本をすべて取り出す
  $stmt = $db->prepare('SELECT
      mp.code,mp.name,mp.price,mp.image
    FROM
      mst_product mp
    WHERE
      1
  ');
  $stmt->execute();


  $db = null;
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
      <h1>古本のアルジ</h1>
      <p>〜品質そこそこ 古本販売サイト〜</p><br>
      <?php if ($_SESSION['cus_login']['now']) : ?>
        <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
        <a class="button white" href="member_logout.php">ログアウト</a>
      <?php else : ?>
        <p>ゲストさん、ようこそ</p><br>
        <a class="button white" href="member_login.php">会員ログイン</a>
        <div></div>
      <?php endif; ?>
    </header>
    <main>
      <h2 class="heading">古本一覧</h2>
      <div class="book-lists">
        <?php while(true) : ?>
          <?php $rec = $stmt->fetch(); ?>
          <?php if ($rec == false) break; ?>
            <a class="book-list" href="shop_product.php?procode=<?php echo h($rec['code']); ?>">
              <img src="../product/pro_picture/<?php echo h($rec['image']); ?>">
              <br>
              <div class=" black">
                『<?php echo h($rec['name']); ?>』　
                <?php echo h($rec['price']); ?>円
              </div>
            </a>
            <br>
        <?php endwhile; ?>
      </div>
      <br>
        <a class="button black" href="shop_cartlook.php">
          カートを見る
        </a>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
