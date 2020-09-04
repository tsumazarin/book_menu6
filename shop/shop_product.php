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
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1>
      <?php if ($_SESSION['cus_login']['now']) : ?>
        <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
        <a class="button white" href="member_logout.php">ログアウト</a>
      <?php else : ?>
        <p>ゲストさん、ようこそ</p><br>
        <a class="button white" href="member_login.php">会員ログイン</a>
      <?php endif; ?>
    </header>
    <main>
      <h2 class="heading">古本参照</h2>
      <dl class="product-wrapper clearfix">
        <div class="left">
          <dt>
            古本コード：
            <span class="bold">
              <?php echo h($rec['code']); ?>
            </span>
          </dt>
          <br>
          <dt>
            タイトル：
            <span class="bold">
              『<?php echo h($rec['name']); ?>』
            </span>
          </dt>
          <br>
          <dt>
            価格：
            <span class="bold">
              <?php echo h($rec['price']); ?>円
            </span>
          </dt>
        </div>
        <div class="right">
          <img src="../product/pro_picture/<?php echo h($rec['image']) ?>" alt="<?php echo h($rec['name']); ?>">
        </div>
      </dl>
      <a class="button black" href="shop_list.php">戻る</a> |
      <?php if(in_array($rec['code'], $carts) == true) : ?>
        <p>カートに入っています</p>
      <?php else : ?>
        <a class="button black" href="shop_cartin.php?procode=<?php echo h($rec['code']); ?>">
          カートに入れる
        </a>
      <?php endif; ?>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
