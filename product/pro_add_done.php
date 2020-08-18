<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['login']['now'])==false){
    header('Location: ../staff_login/staff_login.php');
    exit();
  }

  $login_name=$_SESSION['login']['name'];
  $login_code=$_SESSION['login']['code'];

  $name=$_SESSION['product']['name'];
  unset($_SESSION['product']);
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本市場</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>古本追加</h2>
    </div>
    <br><br>
    <div class="midashi-wrapper menu">
      <p>『<?php echo h($name); ?>』を追加しました</p>
    </div>
    <br>
    <a class="button" href="pro_list.php">戻る</a>
  </body>
</html>
