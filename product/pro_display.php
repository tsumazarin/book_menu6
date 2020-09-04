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

  $stmt = $db->prepare('SELECT * FROM mst_product WHERE code=?');
  $stmt->execute(array($product_code));
  $rec = $stmt->fetch();
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
      <h2>古本参照</h2>
      <div class="product-content-wrapper clearfix">
        <div class="left">
          <table class="product-content">
            <tr>
              <td>古本コード</td>
              <td>
                <span class="border-bottom">
                  <?php echo h($rec['code']); ?>
                </span>
              </td>
            </tr>
            <tr>
              <td>タイトル</td>
              <td>
                <span class="border-bottom">
                  『<?php echo h($rec['name']); ?>』
                </span>
              </td>
            </tr>
            <tr>
              <td>価格</td>
              <td>
                <span class="border-bottom">
                  <?php echo h($rec['price']); ?>円
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div class="right">
          <img src="./pro_picture/<?php echo h($rec['image']) ?>" alt="<?php echo h($rec['name']); ?>">
        </div>
      </div>
      <br><br>
      <a class="button black" href="pro_list.php">古本一覧へ</a>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
