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

  $code=$_SESSION['staff']['code'];

  $stmt=$db->prepare('SELECT * FROM mst_staff WHERE code=?');
  $stmt->execute(array($code));
  $rec=$stmt->fetch();
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
      <h2>スタッフ参照</h2>
    </div>
    <dl>
      <dt class="menu">スタッフコード：<?php echo h($rec['code']); ?></dt>
      <dd></dd>
      <br>
      <dt class="menu">スタッフ名：<?php echo h($rec['name']); ?></dt>
      <dd></dd>
      <br>
      <dt class="menu">パスワード：【表示されません】</dt>
      <dd></dd>
    </dl>
    <br><br>
    <a class="button" href="staff_list.php">スタッフ一覧へ</a>
  </body>
</html>
