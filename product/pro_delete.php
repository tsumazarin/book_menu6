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
    <header>
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section><br>
      <p><?php echo h($login_name); ?>さん、ログイン中</p>
    </header>
    <main>
      <h2>古本削除</h2>
      <form action="" method="post">
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
            </table>
          </div>
          <div class="right">
            <img src="./pro_picture/<?php echo h($rec['image']); ?>" alt="">
          </div>
        </div>
        <p>この古本を削除してよろしいでしょうか？</p>
        <br>
        <input class="button black" type="button" onclick="history.back()" value="戻る"> |
        <input class="button black" type="submit" name="done" value="削除">
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
