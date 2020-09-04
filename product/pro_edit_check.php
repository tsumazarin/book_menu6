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

  //修正後の古本タイトル、値段、画像を取得
  $product_name = $_SESSION['product']['name'];
  $product_price = $_SESSION['product']['price'];
  $product_image = $_SESSION['product']['image'];

  //「修正」ボタンを押して・・・
  if (isset($_POST['done']) == true) {
    $product_code = $_SESSION['product']['code'];
    $product_name = $_SESSION['product']['name'];
    $product_price = $_SESSION['product']['price'];
    $product_image = $_SESSION['product']['image'];
    $product_old_image = $_SESSION['product']['old_image'];

    //修正
    $stmt = $db->prepare('UPDATE mst_product
      SET
        name=?,
        price=?,
        image=?
      WHERE
        code=?
    ');
    $stmt->execute(array(
      $product_name,
      $product_price,
      $product_image['name'],
      $product_code
    ));

    //古い画像を削除
    if ($product_image['name'] != $product_old_image) {

      unlink("./pro_picture/{$product_old_image}");
    }

    $db = null;

    header('Location: pro_edit_done.php');
    exit();
  }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section><br>
      <p><?php echo h($login_name); ?>さん、ログイン中</p>
    </header>
    <main>
      <h2>古本修正</h2>
      <form action="" method="post">
        <div class="product-content-wrapper clearfix">
          <div class="left">
            <table class="product-content">
              <tr>
                <td>タイトル</td>
                <td>
                  <span class="border-bottom">
                    <?php echo h($product_name); ?>
                  </span>
                </td>
              </tr>
              <tr>
                <td>価格</td>
                <td>
                  <span class="border-bottom">
                    <?php echo h($product_price); ?>円
                  </span>
                </td>
              </tr>
            </table>
          </div>
          <div class="right">
            <img src="./pro_picture/<?php echo h($product_image['name']); ?>" alt="">
          </div>
        </div>
        <input class="button black" type="button" onclick="history.back()" value="戻る"> |
        <input class="button black" type="submit" name="done" value="修正">
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
