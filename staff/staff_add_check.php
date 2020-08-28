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

  $name=$_SESSION['staff']['name'];

  if(isset($_POST['done'])==true){
    //スタッフをデータベースに登録
    if(isset($_SESSION['staff']['name'])==true){
      $name=$_SESSION['staff']['name'];
      $pass=$_SESSION['staff']['pass'];

      $stmt=$db->prepare('INSERT INTO mst_staff(name,password) VALUES (?,?)');
      $stmt->execute(array($name,$pass));

      $db=null;

      header('Location: staff_add_done.php');
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
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>スタッフ追加</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="menu">お名前：<?php echo h($name); ?></dt>
        <dd></dd>
        <br>
        <dt class="menu">パスワード：【表示されません】</dt>
        <dd></dd>
      </dl>
      <br><br>
      <div class="menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="追加">
      </div>
    </form>
  </body>
</html>
