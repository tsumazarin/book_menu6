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

  //スタッフを取り出す
  $stmt = $db->prepare('SELECT ms.code,ms.name,ms.password
    FROM
      mst_staff ms
    WHERE
      code=?
  ');
  $stmt->execute(array($staff_code));
  $rec = $stmt->fetch();

  if (isset($_POST['done']) == true) {
    if ($_POST['pass'] == '') {
      $error['pass'] = 'blank';
    }elseif (md5($_POST['pass']) != $rec['password']) {
      $error['pass'] = 'wrong';
    }
    if (empty($error)) {
      header('Location: staff_delete_done.php');
      exit();
    }
  }

  $db = null;
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
      <h2>スタッフ削除</h2>
    </div>
    <form action="" method="post">
      <dl>
        <dt class="input_title">スタッフコード：<?php echo h($rec['code']); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">スタッフ名：<?php echo h($rec['name']); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">このスタッフのパスワードを入力してください</dt>
        <dd>
          <input class="input_content" type="password" name="pass" value="<?php echo h($_POST['pass']); ?>">
          <?php if ($error['pass'] == 'blank') : ?>
            <p>※　パスワードを入力してください</p>
          <?php endif; ?>
          <?php if ($error['pass'] == 'wrong') : ?>
            <p>※　パスワードが間違っています</p>
          <?php endif; ?>
        </dd>
      </dl>
      <br>
      <p class="register menu">このスタッフを削除してよろしいでしょうか？</p>
      <br>
      <div class="menu">
        <input
          class="button"
          type="button"
          onclick="history.back()"
          value="戻る"
        >
         |
        <input class="button" type="submit" name="done" value="削除">
      </div>
    </form>
  </body>
</html>
