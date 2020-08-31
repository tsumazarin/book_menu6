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

  $staff_code = $_SESSION['staff']['code'];

  //選択されたスタッフを取り出す
  $stmt = $db->prepare('SELECT ms.name FROM mst_staff ms WHERE code=?');
  $stmt->execute(array($staff_code));
  $rec = $stmt->fetch();

  $db = null;

  //「確認」送信
  if (isset($_POST['check']) == true) {
    if ($_POST['name'] == '') {
      $error['name'] = 'blank';
    }
    if ($_POST['pass'] == '') {
      $error['pass'] = 'blank';
    }
    if ($_POST['pass'] != $_POST['pass2']) {
      $error['pass'] = 'wrong';
    }
    //エラーなし
    if (empty($error)) {
      $_SESSION['staff']['name'] = $_POST['name'];
      $_SESSION['staff']['pass'] = md5($_POST['pass']);

      header('Location: staff_edit_check.php');
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
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>スタッフ修正</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">スタッフコード：<?php echo h($staff_code); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">スタッフ名</dt>
        <dd>
          <input
            class="input_content"
            type="text"
            name="name"
            size="35"
            value="<?php echo h($rec['name']); ?>"
          >
          <?php if ($error['name'] == 'blank') : ?>
            <p>※　お名前を記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">パスワードを再設定してください</dt>
        <dd>
          <input
            class="input_content"
            type="password"
            name="pass"
            size="15"
            value="<?php echo h($_POST['pass']); ?>"
          >
          <?php if ($error['pass'] == 'blank') : ?>
            <p>※　パスワードを記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">パスワードをもう１度入力してください</dt>
        <dd>
          <input
            class="input_content"
            type="password"
            name="pass2"
            size="15"
            value=""
          >
          <?php if ($error['pass'] == 'wrong') : ?>
            <p>※　パスワードが一致しません</p>
          <?php endif; ?>
        </dd>
      </dl>
      <div class="menu">
        <input
          class="button"
          type="button"
          onclick="history.back()"
          value="戻る"
        >
         |
        <input class="button" type="submit" name="check" value="確認">
      </div>
    </form>
  </body>
</html>
