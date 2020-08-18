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

  //スタッフ追加
  if(isset($_POST['add'])==true){
    header('Location: staff_add.php');
    exit();
  }

  if(!empty($_POST['staff_code'])){
    $_SESSION['staff']['code']=$_POST['staff_code'];

    //スタッフ参照
    if(isset($_POST['display'])==true){
      header('Location: staff_display.php');
      exit();
    }

    //スタッフ修正
    if(isset($_POST['edit'])==true){
      header('Location: staff_edit.php');
      exit();
    }

    //スタッフ削除
    if(isset($_POST['delete'])==true){
      header('Location: staff_delete.php');
      exit();
    }
  }

  //スタッフ全員を取り出す
  $stmt=$db->prepare('SELECT ms.code,ms.name FROM book.mst_staff ms WHERE 1');
  $stmt->execute();


  $db=null;
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
      <h2>スタッフ一覧</h2>
    </div>

    <form action="" method="post">
      <?php while(true): ?>
        <?php $rec=$stmt->fetch(); ?>
        <?php if($rec==false){break;} ?>
        <input type="radio" name="staff_code" value="<?php echo h($rec['code']); ?>">
        <?php echo h($rec['name']); ?>
        <br>
      <?php endwhile; ?>
      <br><br>
      <input class="button" type="submit" name="display" value="参照"> |
      <input class="button" type="submit" name="add" value="追加"> |
      <input class="button" type="submit" name="edit" value="修正"> |
      <input class="button" type="submit" name="delete" value="削除">
    </form>
    <br><br>
    <a class="button" href="../staff_login/staff_top.php">トップメニューへ</a>
  </body>
</html>
