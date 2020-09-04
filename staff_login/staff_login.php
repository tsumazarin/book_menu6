<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //「ログイン」ボタンを押して・・・
  if (isset($_POST['check']) == true) {

    //ログインチェック
    if ($_POST['code'] != '' && $_POST['pass'] != '') {

      $login_code = mb_convert_kana($_POST['code'], 'n', 'utf8');
      $login_pass = mb_convert_kana($_POST['pass'], 'n', 'utf8');
      $login_pass = md5($login_pass);

      //ログイン処理
      $stmt = $db->prepare('SELECT ms.code,ms.name
        FROM
          mst_staff ms
        WHERE
          code=?
          AND
          password=?
        ');
      $stmt->execute(array(
        $login_code,
        $login_pass
      ));
      $rec = $stmt->fetch();

      $db = null;

      //ログイン成功かどうか
      if ($rec == true) {

        //ログインしたスタッフをセッションに保存
        $_SESSION['login']['now'] = 1;
        $_SESSION['login']['code'] = $rec['code'];
        $_SESSION['login']['name'] = $rec['name'];

        header('Location: staff_top.php');
        exit();
      }else{
        $error['login']='wrong';
      }
    }else{
      $error['login']='blank';
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
      <h2>スタッフログイン</h2>
      <form action="" method="post">
        <dl>
          <dt>スタッフコード</dt>
          <dd>
            <input type="text" name="code" value="<?php echo h($_POST['code']); ?>">
            <?php if ($error['login'] == 'blank') : ?>
              <p>※　スタッフコードとパスワード両方ご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'wrong') : ?>
              <p>※　スタッフコードもしくはパスワードが間違っています</p>
            <?php endif; ?>
          </dd>
          <br>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="pass" value="<?php echo h($_POST['pass']); ?>">
          </dd>
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
