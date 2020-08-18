<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['login']['now'])==false){
    header('Location: staff_login.php');
    exit();
  }

  $login_name=$_SESSION['login']['name'];
  $login_code=$_SESSION['login']['code'];

  //注文ダウンロード
  if(isset($_POST['download'])==true){
    $year=$_POST['year'];
    $month=$_POST['month'];
    $day=$_POST['day'];

    $stmt=$db->prepare('SELECT
        ds.code,
        ds.date,
        ds.code_member,
        ds.name AS cus_name,
        ds.email,
        ds.postal,
        ds.address,
        ds.tel,
        sp.code_product,
        mp.name AS pro_name,
        sp.price,
        sp.quantity
      FROM
        dat_sales ds,
        dat_sales_product sp,
        mst_product mp
      WHERE
        ds.code=sp.code_sales
        AND sp.code_product=mp.code
        AND substr(ds.date,1,4)=?
        AND substr(ds.date,6,2)=?
        AND substr(ds.date,9,2)=?');
    $stmt->execute(array($year,$month,$day));

    $db=null;

    $csv="注文コード、注文日時、会員番号、お名前、メール、郵便番号、住所、TEL、商品コード、商品名、価格、数量";
    $csv.="\n";
    while(true){
      $rec=$stmt->fetch();
      if($rec==false){
        break;
      }
      $csv.="{$rec['code']},";
      $csv.="{$rec['date']},";
      $csv.="{$rec['code_member']},";
      $csv.="{$rec['cus_name']},";
      $csv.="{$rec['email']},";
      $csv.="{$rec['postal']},";
      $csv.="{$rec['address']},";
      $csv.="{$rec['tel']},";
      $csv.="{$rec['code_product']},";
      $csv.="{$rec['pro_name']},";
      $csv.="{$rec['price']},";
      $csv.="{$rec['quantity']},";
      $csv.="\n";

      //chumon.csv書き込み
      $file=fopen('./chumon.csv','w');
      $csv2=mb_convert_encoding($csv,'SJIS','utf-8');
      fputs($file,$csv2);
      fclose($file);
    }
  }else{
    header('Location: order_download.php');
    exit();
  }


?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本市場</title>
  </head>
  <body>
    <p><?php echo h($login_name); ?>さん、ログイン中</p>
    <div class="midashi-wrapper">
      <h2>注文ダウンロード</h2>
    </div>
    <br>
    <br>
    <div class="midashi-wrapper">
      <a class="button menu" href="chumon.csv">注文データダウンロード</a>
      <br><br><br>
      <a class="button menu" href="../staff_login/staff_top.php">トップメニューへ</a>
      <br><br><br>
    </div>
    <p class="register">以下の内容をダウンロードします</p>
    <hr>
    <?php echo nl2br(h($csv)); ?>
  </body>
</html>
