<?php
  session_start();
  require('../htmlspecialchars.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['login']['now'])==false){
    header('Location: staff_login.php');
    exit();
  }

  $login_name=$_SESSION['login']['name'];
  $login_code=$_SESSION['login']['code'];
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本の主人</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <a class="button logout" href="staff_logout.php">ログアウト</a>
    <div class="midashi-wrapper">
      <h2>管理トップメニュー</h2>
    </div>
    <br><br>
    <div class="midashi-wrapper">
      <a class="button menu" href="../staff/staff_list.php">スタッフ管理</a>
      <dd></dd>
      <br><br>
      <a class="button menu" href="../product/pro_list.php">古本管理</a><br>
      <dd></dd>
      <br><br>
      <a class="button menu" href="../order/order_download.php">注文ダウンロード</a>
      <dd></dd>
    </div>
  </body>
</html>
