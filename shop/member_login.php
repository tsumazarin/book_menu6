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
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section>
    </header>
    <main>
      <h2 class="heading">会員ログイン</h2>
      <form action="" method="post">
        <dl>
          <dt>
            メールアドレス：
            <input type="text" name="email" value="<?php echo h($_POST['email']); ?>">
          </dt>
          <dd>
            <?php if ($error['login'] == 'blank') : ?>
              <br>
              <p>※　メールアドレスとパスワード両方ご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'wrong') : ?>
              <p>※　メールアドレスもしくはパスワードが間違っています</p>
            <?php endif; ?>
          </dd>
          <br>
          <dt>
            パスワード：
            <input type="password" name="pass" value="<?php echo h($_POST['pass']); ?>">
          </dt>
          <dd></dd>
        </dl>
        <input class="button black" type="submit" name="check" value="ログイン">
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
