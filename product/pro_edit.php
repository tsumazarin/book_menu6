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

  //選択された古本を取り出す
  $product_code = $_SESSION['product']['code'];

  $stmt = $db->prepare('SELECT mp.name,mp.price,mp.image
    FROM
      mst_product mp
    WHERE
      code=?
  ');
  $stmt->execute(array($product_code));
  $rec = $stmt->fetch();

  //選択された画像をセッションに保存
  $_SESSION['product']['old_image'] = $rec['image'];

  $db = null;

  //「確認」ボタンを押して・・・
  if (isset($_POST['check']) == true) {

    //エラー確認

    //タイトルチェック
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }

    //値段チェック
    $product_price = mb_convert_kana($_POST['price'], 'n', 'utf8');
    if ($_POST['price'] == '') {
      $error['price'] = 'blank';
    }elseif (is_numeric($product_price) == false) {
      $error['price'] = 'wrong';
    }elseif ($product_price < 10 || 10000 < $product_price) {
      $error['price'] = 'size';
    }

    //画像チェック
    $product_image = $_FILES['image'];
    $ext = substr($product_image['name'], -3);

    if ($product_image['size'] <= 0) {
      $error['image'] = 'blank';
    }elseif ($product_image['size'] > 10000000) {
      $error['image'] = 'size';
    }elseif (
      $ext != 'PEG'
      &&
      $ext != 'peg'
      &&
      $ext != 'png'
      &&
      $ext != 'PNG'
      &&
      $ext != 'jpg'
      &&
      $ext != 'JPG') {
      $error['image'] = 'type';
    }

    //エラーなし
    if (empty($error)) {
      $_SESSION['product']['name'] = $_POST['name'];
      $_SESSION['product']['price'] = $product_price;
      $_SESSION['product']['image'] = $product_image;

      //画像をアップロード
      move_uploaded_file($product_image['tmp_name'], "./pro_picture/{$product_image['name']}");

      header('Location: pro_edit_check.php');
      exit();
    }
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
      <form action="" method="post" enctype="multipart/form-data">
        <table class="product-form">
          <tr>
            <td>古本コード</td>
            <td>
              <span class="border-bottom">
                <?php echo h($product_code); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>タイトル</td>
            <td>
              <input type="text" name="name" size="15" value="<?php echo h($rec['name']); ?>">
              <?php if($error['name']=='blank'): ?>
                <p>※　タイトルを記入してください</p>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td>価格を再設定してください</td>
            <td>
              <input type="text" id="price" name="price" size="15" value="<?php echo h($rec['price']); ?>">
              <label for="price">円</label>
              <?php if ($error['price'] == 'blank') : ?>
                <p>※　価格を記入してください</p>
              <?php endif; ?>
              <?php if ($error['price'] == 'wrong') : ?>
                <p>※　数字で記入してください</p>
              <?php endif; ?>
              <?php if ($error['price'] == 'size') : ?>
                <p>※　10円以上10000円以内で設定してください</p>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td>写真</td>
            <td>
              <input type="file" name="image">
              <?php if ($error['image'] == 'blank') : ?>
                <p>※　画像を改めて設定してください</p>
              <?php endif; ?>
              <?php if ($error['image'] == 'size') : ?>
                <p>※　画像が大きすぎます</p>
              <?php endif; ?>
              <?php if ($error['image'] == 'type') : ?>
                <p>※　「JPEG」「PNG」「JPG」「jpeg」「png」「jpg」いずれかの拡張子で指定してください</p>
              <?php endif; ?>
            </td>
          </tr>
        </table>
        <div>
          <input class="button black" type="button" onclick="history.back()" value="戻る"> |
          <input class="button black" type="submit" name="check" value="確認">
        </div>
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
