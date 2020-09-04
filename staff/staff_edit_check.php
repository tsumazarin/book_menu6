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
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section><br>
      <p><?php echo h($login_name); ?>さん、ログイン中</p>
    </header>
    <main>
      <h2>スタッフ修正</h2>
      <form action="" method="post">
        <table class="staff-form">
          <tr>
            <td>お名前</td>
            <td>
              <span class="border-bottom">
                <?php echo h($staff_name); ?>
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
        <input class="button black" type="button" onclick="history.back()" value="戻る"> |
        <input class="button black" type="submit" name="done" value="修正">
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
