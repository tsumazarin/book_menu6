<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['login']['now']) == false) {
    header('Location: ../staff_login/staff_login.php');
    exit();
  }

  $login_name = $_SESSION['login']['name'];
  $login_code = $_SESSION['login']['code'];

  //選択された古本コードを取得
  $product_code = $_SESSION['product']['code'];

  //削除する古本を取り出す
  $stmt = $db->prepare('SELECT mp.code,mp.name,mp.image
    FROM
      mst_product mp
    WHERE
      code=?
  ');
  $stmt->execute(array($product_code));
  $rec = $stmt->fetch();

  //「削除」ボタンを押して・・・
  if (isset($_POST['done']) == true) {
    $_SESSION['product']['image'] = $rec['image'];
    header('Location: pro_delete_done.php');
    exit();
  }

  $db=null;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>古本削除 | 古本販売サイト</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">古本コード：<?php echo h($rec['code']); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">タイトル：『<?php echo h($rec['name']); ?>』</dt>
        <dd></dd>
        <br>
        <img class="input_title" src="./pro_picture/<?php echo h($rec['image']); ?>" alt="">
      </dl>
      <br>
      <p class="register input_title">この古本を削除してよろしいでしょうか？</p>
      <br>
      <div class="menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="削除">
      </div>
    </form>
  </body>
</html>
