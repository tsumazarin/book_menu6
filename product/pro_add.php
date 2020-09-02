<?php
  session_start();

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['login']['now']) == false) {
    header('Location: ../staff_login/staff_login.php');
    exit();
  }

  $login_name = $_SESSION['login']['name'];
  $login_code = $_SESSION['login']['code'];

  //「確認」ボタンを押して・・・
  if (isset($_POST['check']) == true) {

    //エラー確認

    //タイトルチェック
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }

    //値段チェック
    $price = mb_convert_kana($_POST['price'], 'n', 'utf8');
    if ($_POST['price'] == '') {
      $error['price'] = 'blank';
    }elseif (is_numeric($price) == false) {
      $error['price'] = 'wrong';
    }elseif ($price < 10 || 10000 < $price) {
      $error['price'] = 'size';
    }

    //画像チェック
    $image = $_FILES['image'];
    $ext = substr($image['name'], -3);
    if ($image['size']<= 0) {
      $error['image'] = 'blank';
    }elseif ($image['size'] > 10000000) {
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
      $_SESSION['product']['price'] = $price;
      $_SESSION['product']['image'] = $image;

      //画像をアップロード
      move_uploaded_file($image['tmp_name'], "./pro_picture/{$image['name']}");

      header('Location: pro_add_check.php');
      exit();
    }
  }

  //htmlspecialchrasのショートカット
  require('../htmlspecialchars.php');
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
      <h2>古本追加</h2>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
      <dl>
        <dt class="input_title">タイトルを追加してください</dt>
        <dd>
          <input class="input_content" type="text" name="name" size="35" value="<?php echo h($_POST['name']); ?>">
          <?php if ($error['name'] == 'blank') : ?>
            <p>※　タイトルを記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">価格を設定してください</dt>
        <dd>
          <input class="input_content" id="pro_price" type="text" name="price" size="15" value="<?php echo h($price); ?>">
          <label for="pro_price">円</label>
          <?php if ($error['price'] == 'blank') : ?>
            <p>※　価格を記入してください</p>
          <?php endif; ?>
          <?php if ($error['price'] == 'wrong') : ?>
            <p>※　数字で入力してください</p>
          <?php endif; ?>
          <?php if ($error['price'] == 'size') : ?>
            <p>※　10円以上10000円以内で設定してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">
          写真：
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
        </dt>
        <dd>
        </dd>
      </dl>
      <br>
      <div class="menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="check" value="確認">
      </div>
    </form>
  </body>
</html>
