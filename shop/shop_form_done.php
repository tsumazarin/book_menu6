<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $name=$_SESSION['cus']['name'];
  $email=$_SESSION['cus']['email'];
  $postal=$_SESSION['cus']['postal'];
  $address=$_SESSION['cus']['address'];
  $tel=$_SESSION['cus']['tel'];

  $order=$_SESSION['cus']['order'];

  $carts=$_SESSION['cart'];
  $number=$_SESSION['number'];
  $max=count($carts);

  $pay=$_SESSION['pay'];

  //メールの本文
  $honbun='';
  $honbun.="{$name}様\n\nこのたびはご注文ありがとうございました。\n";
  $honbun.="\n";
  $honbun.="ご注文商品\n";
  $honbun.="------------\n";
  for($i=0; $i<$max; $i++){
    $stmt=$db->prepare('SELECT mp.name,mp.price
      FROM
        mst_product mp
      WHERE
        code=?
    ');
    $data[0]=$carts[$i];
    $stmt->execute($data);
    $rec=$stmt->fetch();

    $total=$rec['price']*$number[$i];

    $honbun.="『{$rec['name']}』";
    $honbun.="{$rec['price']}円×";
    $honbun.="{$number[$i]}コ＝";
    $honbun.="{$total}円\n";
  }
  $db=null;

  if($pay=='cash'){
    $honbun.="送料は無料です。\n";
    $honbun.="------------\n";
    $honbun.="\n";
    $honbun.="代金は以下の口座にお振込ください。\n";
    $honbun.="つま銀行 ざりん支店 普通口座 1234567\n";
    $honbun.="入金確認が取れ次第、発送させていただきます。\n";
    $honbun.="\n";
  }

  if($pay=='card'){
    $honbun.="カード支払いが完了しました。\n";
  }

  if($order=='order_register'){
    $honbun.="会員登録が完了いたしました。\n";
    $honbun.="次回からはメールアドレスとパスワードでログインしてください。\n";
    $honbun.="ご注文が簡単にできるようになります。\n";
    $honbun.="\n";
  }

  $honbun.="□□□□□□□□□□□□□□□□\n";
  $honbun.="〜品質そこそこ古本のアルジ〜\n";
  $honbun.="\n";
  $honbun.="沖縄県那覇市恩納村123-4\n";
  $honbun.="電話 090-6060-7843\n";
  $honbun.="メール：info@huruhonichiba.co.jp\n";
  $honbun.="□□□□□□□□□□□□□□□□\n";
  //メール終了

  //お客様にメール送信
  $title="ご注文ありがとうございます。";
  $header='From: info@huruhonichiba.co.jp';
  $honbun=html_entity_decode($honbun, ENT_QUOTES, 'utf-8');
  mb_language('Japanese');
  mb_internal_encoding('utf-8');
  mb_send_mail($email,$title,$honbun,$header);

  //お店にメール送信
  $title="ご注文ありがとうございます。";
  $header="From: {$email}";
  $honbun=html_entity_decode($honbun, ENT_QUOTES, 'utf-8');
  mb_language('Japanese');
  mb_internal_encoding('utf-8');
  mb_send_mail('info@huruhonichiba.co.jp',$title,$honbun,$header);

  unset($_SESSION['cus']);
  unset($_SESSION['cart']);
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
        <?php echo h($name); ?>様、ご注文ありがとうございました。<br>
        <?php echo h($email); ?>にメールを送りましたのでご確認ください。<br>
        商品は以下の住所に発送させていただきます。<br>
        〒　<?php echo h($postal); ?><br>
        住所：<?php echo h($address); ?><br>
        電話番号：<?php echo h($tel); ?><br>
        <?php if($order=='order_register'): ?>
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
    <p><?php echo nl2br(h($honbun)); ?></p>
    <hr>
  </body>
</html>
