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
  $price=$_SESSION['product']['price'];
  $image=$_SESSION['product']['image'];

  //「修正」ボタン
  if(isset($_POST['done'])==true){
    $code=$_SESSION['product']['code'];
    $name=$_SESSION['product']['name'];
    $price=$_SESSION['product']['price'];
    $image=$_SESSION['product']['image'];
    $old_image=$_SESSION['product']['old_image'];

    //修正
    $stmt=$db->prepare('UPDATE mst_product SET name=?,price=?,image=? WHERE code=?');
    $stmt->execute(array($name,$price,$image['name'],$code));

    if($image['name']!=$old_image){
      //古い画像を削除
      unlink("./pro_picture/{$old_image}");
    }

    $db=null;

    header('Location: pro_edit_done.php');
    exit();
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
      <h2>古本修正</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">タイトル：<?php echo h($name); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">価格：<?php echo h($price); ?>円</dt>
        <dd></dd>
        <br>
        <img class="input_title" src="./pro_picture/<?php echo h($image['name']); ?>" alt="">
      </dl>
      <br><br>
      <div class="menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="修正">
      </div>
    </form>
    <footer></footer>
  </body>
</html>
