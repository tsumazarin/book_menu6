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

  $stmt = $db->prepare('SELECT * FROM mst_staff WHERE code=?');
  $stmt->execute(array($staff_code));
  $rec = $stmt->fetch();
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
      <h2>スタッフ参照</h2>
      <table class="staff-form">
        <tr>
          <td>スタッフコード</td>
          <td>
            <span class="border-bottom">
              <?php echo h($rec['code']); ?>
            </span>
          </td>
        </tr>
        <tr>
          <td>スタッフ名</td>
          <td>
            <span class="border-bottom">
              <?php echo h($rec['name']); ?>
            </span>
          </td>
        </tr>
        <tr>
          <td>パスワード</td>
          <td>
            <span class="border-bottom">
              【表示されません】
            </span>
          </td>
        </tr>
      </table>
      <a class="button black" href="staff_list.php">スタッフ一覧へ</a>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
