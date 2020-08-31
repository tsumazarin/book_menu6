<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  if (isset($_POST['check']) == true) {
    //エラーの検査
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

      if ($rec == true) {
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
    <title>古本のアルジ</title>
  </head>
  <body>
    <div class="midashi-wrapper">
      <h2>スタッフログイン</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">スタッフコード</dt>
        <dd>
          <input
            class="input_content"
            type="text"
            name="code"
            value="<?php echo h($_POST['code']); ?>"
          >
          <?php if ($error['login'] == 'blank') : ?>
            <p>※　スタッフコードとパスワード両方ご記入ください</p>
          <?php endif; ?>
          <?php if ($error['login'] == 'wrong') : ?>
            <p>※　スタッフコードもしくはパスワードが間違っています</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">パスワード</dt>
        <dd>
          <input
            class="input_content"
            type="password"
            name="pass"
            value="<?php echo h($_POST['pass']); ?>"
          >
        </dd>
      </dl>
      <input class="button mail" type="submit" name="check" value="ログイン">
    </form>
  </body>
</html>
