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

  //スタッフを削除
  $stmt = $db->prepare('DELETE FROM mst_staff WHERE code=?');
  $stmt->execute(array($staff_code));
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
      <h2>スタッフ削除</h2>
      <p>削除完了しました</p>
      <br>
      <a class="button black" href="staff_list.php">スタッフ一覧へ</a>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
