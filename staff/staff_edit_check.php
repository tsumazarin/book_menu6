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

  $staff_name = $_SESSION['staff']['name'];

  //「修正」ボタン
  if (isset($_POST['done']) == true) {
    $staff_code = $_SESSION['staff']['code'];
    $staff_name = $_SESSION['staff']['name'];
    $staff_pass = $_SESSION['staff']['pass'];

    //修正
    $stmt = $db->prepare('UPDATE mst_staff
      SET
        name=?,
        password=?
      WHERE
        code=?
    ');
    $stmt->execute(array(
      $staff_name,
      $staff_pass,
      $staff_code
    ));

    $db = null;

    header('Location: staff_edit_done.php');
    exit();
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
        <dt class="input_title">お名前：<?php echo h($staff_name); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">パスワード：【表示されません】</dt>
        <dd></dd>
      </dl>
      <div class="do_login">
        <input
          class="button"
          type="button"
          onclick="history.back()"
          value="戻る"
        >
         |
        <input class="button" type="submit" name="done" value="修正">
      </div>
    </form>
  </body>
</html>
