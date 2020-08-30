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

  //SESSIONで保存
  $_SESSION['mail_content'] = $content;
