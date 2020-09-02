<?php
  session_start();
  require('../htmlspecialchars.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['login']['now']) == false) {
    header('Location: staff_login.php');
    exit();
  }

  $login_name = $_SESSION['login']['name'];
  $login_code = $_SESSION['login']['code'];
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
      <h2>注文ダウンロード</h2>
    </div>
    <div class="midashi-wrapper menu">
      <p class="register">ダウンロードしたい注文日を選んでください</p>
      <form action="order_download_done.php" method="post">
        <select name="year">
          <option value="2017">2017</option>
          <option value="2018">2018</option>
          <option value="2019">2019</option>
          <option value="2020">2020</option>
        </select>
        年
        <select name="month">
          <?php for ($i = 1; $i < 13; $i++) : ?>
            <?php if ($i < 10) : ?>
              <?php $i = sprintf('%02d', $i); ?>
            <?php endif; ?>
            <option value="<?php echo h($i); ?>"><?php echo h($i); ?></option>
          <?php endfor; ?>
        </select>
        月
        <select name="day">
          <?php for ($i = 1; $i < 32; $i++) : ?>
            <?php if ($i < 10) : ?>
              <?php $i = sprintf('%02d', $i); ?>
            <?php endif; ?>
            <option value="<?php echo h($i); ?>"><?php echo h($i); ?></option>
          <?php endfor; ?>
        </select>
        日<br>
        <br>
        <input class="button" type="submit" name="download" value="ダウンロードする">
      </form>
    </div>
  </body>
</html>
