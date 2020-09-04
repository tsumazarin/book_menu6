<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $customer_name = $_SESSION['customer']['name'];
  $customer_email = $_SESSION['customer']['email'];
  $customer_postal = $_SESSION['customer']['postal'];
  $customer_address = $_SESSION['customer']['address'];
  $customer_tel = $_SESSION['customer']['tel'];

  $order = $_SESSION['customer']['order'];

  $carts = $_SESSION['carts'];
  $number = $_SESSION['number'];
  $max = count($carts);

  $pay = $_SESSION['pay'];

  //メールの本文
  $content = '';
  $content .= "{$customer_name}様\n\nこのたびはご注文ありがとうございました。\n";
  $content .= "\n";
  $content .= "ご注文商品\n";
  $content .= "------------\n";
  for ($i = 0; $i < $max; $i++) {
    $stmt = $db->prepare('SELECT mp.name,mp.price
      FROM
        mst_product mp
      WHERE
        code=?
    ');
    $data[0] = $carts[$i];
    $stmt->execute($data);
    $rec = $stmt->fetch();

    $total = $rec['price'] * $number[$i];

    $content .= "『{$rec['name']}』";
    $content .= "{$rec['price']}円×";
    $content .= "{$number[$i]}コ＝";
    $content .= "{$total}円\n";
  }
  $db = null;

  if ($pay == 'cash') {
    $content .= "送料は無料です。\n";
    $content .= "------------\n";
    $content .= "\n";
    $content .= "代金は以下の口座にお振込ください。\n";
    $content .= "つま銀行 ざりん支店 普通口座 1234567\n";
    $content .= "入金確認が取れ次第、発送させていただきます。\n";
    $content .= "\n";
  }

  if ($pay == 'card') {
    $content .= "カード支払いが完了しました。\n";
  }

  if ($order == 'order_register') {
    $content .= "会員登録が完了いたしました。\n";
    $content .= "次回からはメールアドレスとパスワードでログインしてください。\n";
    $content .= "ご注文が簡単にできるようになります。\n";
    $content .= "\n";
  }

  $content .= "□□□□□□□□□□□□□□□□\n";
  $content .= "〜品質そこそこ古本のアルジ〜\n";
  $content .= "\n";
  $content .= "沖縄県那覇市恩納村123-4\n";
  $content .= "電話 090-6060-7843\n";
  $content .= "メール：info@huruhonichiba.co.jp\n";
  $content .= "□□□□□□□□□□□□□□□□\n";
  //メール終了

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
    <title>古本のアルジ | 古本販売サイト</title>
  </head>
  <body>
    <header>
      <h1>古本のアルジ</h1>
    </header>
    <main>
      <h2 class="heading">注文確定</h2><br>
      <p>
        <?php echo h($customer_name); ?>様、ご注文ありがとうございました。<br>
        <?php echo h($customer_email); ?>にメールを送りましたのでご確認ください。<br>
        商品は以下の住所に発送させていただきます。<br>
        〒 <?php echo h($customer_postal); ?><br>
        住所：<?php echo h($customer_address); ?><br>
        電話番号：<?php echo h($customer_tel); ?><br>
        <?php if ($order == 'order_register') : ?>
          会員登録が完了いたしました。<br>
          次回からはメールアドレスとパスワードでログインしてください。<br>
          ご注文が簡単にできるようになります。<br>
        <?php endif; ?>
      </p>
      <br><br><br>
      <a class="button black" href="shop_list.php">古本一覧へ</a><br>
      <br><br>
      <hr>
      <h4>メール内容</h4>
      <p><?php echo nl2br(h($content)); ?></p>
      <hr>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
