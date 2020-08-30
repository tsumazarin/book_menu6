<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $customer_email = $_SESSION['customer']['email'];
  $content = $_SESSION['mail_content'];

  //お客様にメール送信
  $title = "ご注文ありがとうございます。";
  $header = 'From: info@huruhonichiba.co.jp';
  $content = html_entity_decode($content, ENT_QUOTES, 'utf-8');
  mb_language('Japanese');
  mb_internal_encoding('utf-8');
  mb_send_mail($customer_email, $title, $content, $header);

  //お店にメール送信
  $title = "ご注文ありがとうございます。";
  $header = "From: {$customer_email}";
  $content = html_entity_decode($content, ENT_QUOTES, 'utf-8');
  mb_language('Japanese');
  mb_internal_encoding('utf-8');
  mb_send_mail('info@huruhonichiba.co.jp', $title, $content, $header);

  unset($_SESSION['customer']);
  unset($_SESSION['carts']);
  unset($_SESSION['number']);
  unset($_SESSION['price']);

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
      <h2>お客様情報</h2>
    </div>
    <div class="midashi-wrapper mail">
      <p>
        <?php echo h($customer_name); ?>様、ご注文ありがとうございました。<br>
        <?php echo h($customer_email); ?>にメールを送りましたのでご確認ください。<br>
        商品は以下の住所に発送させていただきます。<br>
        〒　<?php echo h($customer_postal); ?><br>
        住所：<?php echo h($customer_address); ?><br>
        電話番号：<?php echo h($customer_tel); ?><br>
        <?php if ($order == 'order_register') : ?>
          会員登録が完了いたしました。<br>
          次回からはメールアドレスとパスワードでログインしてください。<br>
          ご注文が簡単にできるようになります。<br>
        <?php endif; ?>
      </p>
    </div>
    <br><br><br>
    <a class="button" href="shop_list.php">古本一覧へ</a><br>
    <br><br>
    <hr>
    <h4>メール内容</h4>
    <p><?php echo nl2br(h($content)); ?></p>
    <hr>
  </body>
</html>
