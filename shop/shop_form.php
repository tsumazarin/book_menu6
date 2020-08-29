<?php
  session_start();
  session_regenerate_id();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  if (isset($_POST['register']) == true) {
    $order['register'] = 'on';
  }

  if (isset($_POST['check']) == true) {
    //エラー確認
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }
    $email = mb_convert_kana($_POST['email'], 'n', 'utf8');
    if ($_POST['email'] == '') {
      $error['email'] = 'blank';
    }elseif (preg_match('/\A[\w\\.]+\@[\w\\.]+\.([a-z]+)\z/', $email) == 0) {
      $error['email'] = 'wrong';
    }
    $postal = mb_convert_kana($_POST['postal'], 'a', 'utf8');
    if ($_POST['postal'] == '') {
      $error['postal'] = 'blank';
    }elseif (preg_match('/\A\d{3}[-]\d{4}\z/', $postal) == 0) {
      $error['postal'] = 'wrong';
    }
    if ($_POST['address'] == '') {
      $error['address'] = 'blank';
    }
    $tel = mb_convert_kana($_POST['tel'], 'a', 'utf8');
    if ($_POST['tel'] == '') {
      $error['tel'] = 'blank';
    }elseif (preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel) == 0) {
      $error['tel'] = 'wrong';
    }

    if ($_POST['order'] == 'order_register') {
      $pass = mb_convert_kana($_POST['pass'], 'a', 'utf8');
      if ($_POST['pass'] == '') {
        $error['pass'] = 'blank';
      }elseif ($_POST['pass'] != $_POST['pass2']) {
        $error['pass'] = 'wrong';
      }
    }


    //エラーなし
    if (empty($error)) {
      $_SESSION['customer']['name'] = $_POST['name'];
      $_SESSION['customer']['email'] = $email;
      $_SESSION['customer']['postal'] = $postal;
      $_SESSION['customer']['address'] = $_POST['address'];
      $_SESSION['customer']['tel'] = $tel;
      $_SESSION['customer']['order'] = $_POST['order'];
      if ($_POST['order'] == 'order_register') {
        //会員登録に関する
        $_SESSION['customer']['pass'] = md5($pass);
        $_SESSION['customer']['gender'] = $_POST['gender'];
        $_SESSION['customer']['birth'] = $_POST['birth'];
      }

      header('Location: shop_form_check.php');
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
    <h2>お客様情報</h2>
    <p>お客様情報を入力してください</p>
    <div class="clear"></div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">お名前</dt>
        <dd>
          <input class="input_content" type="text" name="name" size="35" value="<?php echo h($_POST['name']); ?>">
          <?php if ($error['name'] == 'blank') : ?>
            <p>※　お名前を記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">メールアドレス</dt>
        <dd>
          <input class="input_content" type="text" name="email" size="35" value="<?php echo h($_POST['email']); ?>">
          <?php if ($error['email'] == 'blank') : ?>
            <p>※　メールアドレスを記入してください</p>
          <?php endif; ?>
          <?php if ($error['email']=='wrong') : ?>
            <p>※　メールアドレスを正確に入力してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">郵便番号</dt>
        <dd>
          <input class="input_content" type="text" name="postal" size="15" value="<?php echo h($_POST['postal']); ?>">
          <?php if ($error['postal'] == 'blank') : ?>
            <p>※　郵便番号を記入してください</p>
          <?php endif; ?>
          <?php if ($error['postal']=='wrong') : ?>
            <p>※　「123-4567」の形で入力してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">住所</dt>
        <dd>
          <input class="input_content" type="text" name="address" size="35" value="<?php echo h($_POST['address']); ?>">
          <?php if ($error['address'] == 'blank') : ?>
            <p>※　住所を記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">電話番号</dt>
        <dd>
          <input class="input_content" type="text" name="tel" size="15" value="<?php echo h($_POST['tel']); ?>">
          <?php if ($error['tel'] == 'blank') : ?>
            <p>※　電話番号を記入してください</p>
          <?php endif; ?>
          <?php if ($error['tel'] == 'wrong') : ?>
            <p>※　○○(○)-○○○○-○○○○の形で入力してください</p>
          <?php endif; ?>
        </dd>
        <br><br>
        <input class="radio" type="radio" name="order" value="order_once">今回だけの注文<br>
        <input class="radio" type="radio" name="order" value="order_register"checked>会員登録して注文
        <input class="button register" type="submit" name="register" value="会員登録する"><br>
        <?php if ($order['register'] == 'on') : ?>
          <br>
          <p class="register">※　会員登録する方は以下の項目も入力してください</p><br>
          <dt class="input_title">パスワードを設定してください</dt>
          <dd>
            <input class="input_content" type="password" name="pass" size="15" value="<?php echo h($_POST['pass']); ?>">
            <?php if ($error['pass'] == 'blank') : ?>
              <p>※　パスワードを記入してください</p>
            <?php endif; ?>
            <?php if ($error['pass'] == 'wrong') : ?>
              <p><※　パスワードが一致しません</p>
            <?php endif; ?>
          </dd>
          <br>
          <dt class="input_title">パスワードをもう１度入力してください</dt>
          <dd>
            <input class="input_content" type="password" name="pass2" size="15" value="">
          </dd>
          <br>
          <dt class="input_title">
            性別
          </dt>
            <input type="radio" name="gender" value="1" checked>男性
            <input type="radio" name="gender" value="2">女性<br>
          <br>
          <dt class="input_title">
            生まれ年
          </dt>
          <select name="birth">
            <?php for ($i = 2020; 1910 < $i; $i -= 10) : ?>
              <option value="<?php echo h($i); ?>"><?php echo h($i); ?>年代</option>
            <?php endfor; ?>
          </select>
        <?php endif; ?>
      </dl>
      <br>
      <div class="check">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="check" value="確認">
      </div>
    </form>
    <footer></footer>
  </body>
</html>
