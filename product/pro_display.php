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

  $code=$_SESSION['product']['code'];

  $stmt=$db->prepare('SELECT * FROM book.mst_product WHERE code=?');
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
      <h2>古本参照</h2>
    </div>
    <dl>
      <dt class="input_title">古本コード：<?php echo h($rec['code']); ?></dt>
      <dd></dd>
      <br>
      <dt class="input_title">タイトル：『<?php echo h($rec['name']); ?>』</dt>
      <dd></dd>
      <br>
      <dt class="input_title">価格：<?php echo h($rec['price']); ?>円</dt>
      <dd></dd>
      <br>
      <img class="input_title" src="./pro_picture/<?php echo h($rec['image']) ?>" alt="<?php echo h($rec['name']); ?>">
    </dl>
    <br><br>
    <a class="button" href="pro_list.php">古本一覧へ</a>
    <footer></footer>
  </body>
</html>
