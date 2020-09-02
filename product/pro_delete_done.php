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

  //選択された古本コード、画像を取得
  $product_code = $_SESSION['product']['code'];
  $product_image = $_SESSION['product']['image'];

  //選択された古本を削除
  $stmt = $db->prepare('DELETE FROM mst_product WHERE code=?');
  $stmt->execute(array($product_code));

  //ファイルから削除
  unlink("./pro_picture/{$product_image}");

  unset($_SESSION['product']);
  $db = null;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>古本削除</h2>
    </div>
    <div class="midashi-wrapper menu">
      <p class="register">削除完了しました</p>
    </div>
    <br>
    <a class="button" href="pro_list.php">古本一覧へ</a>
  </body>
</html>
