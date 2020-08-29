<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  if (isset($_POST['check']) == true) {
    //エラーの検査
    if ($_POST['email'] != '' && $_POST['pass'] != '') {
      $email = mb_convert_kana($_POST['email'], 'a', 'utf8');
      $pass = mb_convert_kana($_POST['pass'], 'n', 'utf8');
      $pass = md5($pass);

      //ログイン処理
      $stmt = $db->prepare('SELECT dm.code,dm.name
        FROM
          dat_member dm
        WHERE
          email=?
          AND
          password=?
      ');
      $stmt->execute(array($email,$pass));
      $rec = $stmt->fetch();

      $db = null;

      #ログイン成功
      if ($rec == true) {
        $_SESSION['cus_login']['now'] = 1;
        $_SESSION['cus_login']['code'] = $rec['code'];
        $_SESSION['cus_login']['name'] = $rec['name'];

        header('Location: shop_list.php');
        exit();
      }else{
        $error['login'] = 'wrong';
      }

    }else{
      $error['login'] = 'blank';
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
    <div class="midashi-wrapper">
      <h2>会員ログイン</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">メールアドレス</dt>
        <dd>
          <input class="input_content" type="text" name="email" value="<?php echo h($_POST['email']); ?>">
          <?php if ($error['login'] == 'blank') : ?>
            <br>
            <p>※　メールアドレスとパスワード両方ご記入ください</p>
          <?php endif; ?>
          <?php if ($error['login'] == 'wrong') : ?>
            <p>※　メールアドレスもしくはパスワードが間違っています</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">パスワード</dt>
        <dd>
          <input class="input_content" type="password" name="pass" value="<?php echo h($_POST['pass']); ?>">
        </dd>
      </dl>
      <input class="button do_login" type="submit" name="check" value="ログイン">
    </form>
  </body>
</html>
