<?php
  session_start();
  require('../htmlspecialchars.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['login']['now']) == false) {
    header('Location: staff_login.php');
    exit();
  }

  $login_name = $_SESSION['login']['name'];
  $login_code = $_SESSION['login']['code'];
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
      <p><?php echo h($login_name); ?>さん、ログイン中</p><br>
      <a class="button white" href="staff_logout.php">ログアウト</a>
    </header>
    <main>
      <h2>管理トップメニュー</h2>
      <br><br>
      <a class="button black" href="../staff/staff_list.php">
        スタッフ管理
      </a>
      <dd></dd>
      <br><br>
      <a class="button black" href="../product/pro_list.php">
        古本管理
      </a><br>
      <dd></dd>
      <br><br>
      <a class="button black" href="../order/order_download.php">
        注文ダウンロード
      </a>
      <dd></dd>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
