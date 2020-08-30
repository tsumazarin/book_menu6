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

  $product_name = $_SESSION['product']['name'];
  $product_price = $_SESSION['product']['price'];
  $product_image = $_SESSION['product']['image'];

  if (isset($_POST['done']) == true) {
    //スタッフをデータベースに登録
    if (isset($_SESSION['product']['name']) == true) {
      $stmt = $db->prepare('INSERT INTO
          mst_product(
            name,
            price,
            image
          )
        VALUES
          (?,?,?)
      ');
      $stmt->execute(array(
        $product_name,
        $product_price,
        $product_image['name']
      ));

      $db = null;

      header('Location: pro_add_done.php');
      exit();
    }
  }

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
      <h2>古本追加</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">タイトル：<?php echo h($product_name); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">価格：<?php echo h($product_price); ?>円</dt>
        <dd></dd>
        <br>
        <img class="input_title" src="./pro_picture/<?php echo h($product_image['name']); ?>" alt="<?php echo h($product_image['name']); ?>">
      </dl>
      <br><br>
      <div class="menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="done" value="追加">
      </div>
    </form>
  </body>
</html>
