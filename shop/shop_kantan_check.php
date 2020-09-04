<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == false) {
    header('Location: shop_cartlook.php');
    exit();
  }

  $login_name = $_SESSION['cus_login']['name'];
  $login_code = $_SESSION['cus_login']['code'];
  $stmt=$db->prepare('SELECT
      dm.name,
      dm.email,
      dm.postal,
      dm.address,
      dm.tel
    FROM
      dat_member dm
    WHERE
      code=?
  ');
  $stmt->execute(array($login_code));
  $rec2 = $stmt->fetch();

  $customer_name = $rec2['name'];
  $customer_email = $rec2['email'];
  $customer_postal = $rec2['postal'];
  $customer_address = $rec2['address'];
  $customer_tel = $rec2['tel'];
  $_SESSION['customer']['name'] = $rec2['name'];
  $_SESSION['customer']['email'] = $rec2['email'];
  $_SESSION['customer']['postal'] = $rec2['postal'];
  $_SESSION['customer']['address'] = $rec2['address'];
  $_SESSION['customer']['tel'] = $rec2['tel'];


  $carts = $_SESSION['carts'];
  $number = $_SESSION['number'];
  $price = $_SESSION['cart_price'];
  $max = count($carts);

  if (isset($_POST['cash']) == true || isset($_POST['card']) == true) {
    //占有ロック
    $stmt=$db->prepare('LOCK TABLES
      dat_sales WRITE,
      dat_sales_product WRITE
    ');
    $stmt->execute();

    //dat_salesにデータを入れる
    $stmt = $db->prepare('INSERT INTO
        dat_sales(
          code_member,
          name,
          email,
          postal,
          address,
          tel
        )
      VALUES
        (?,?,?,?,?,?)
    ');
    $stmt->execute(array(
      $login_code,
      $customer_name,
      $customer_email,
      $customer_postal,
      $customer_address,
      $customer_tel
    ));

    //購入履歴IDの最後を取得
    $stmt = $db->prepare('SELECT LAST_INSERT_ID()');
    $stmt->execute();
    $rec = $stmt->fetch();
    $last_code = $rec['LAST_INSERT_ID()'];

    //dat_sales_productにデータを入れる
    for ($i = 0; $i < $max; $i++) {
      $stmt = $db->prepare('INSERT INTO
          dat_sales_product(
            code_sales,
            code_product,
            price,quantity
          )
        VALUES
          (?,?,?,?)
      ');
      $stmt->execute(array(
        $last_code,
        $carts[$i],
        $price[$i],
        $number[$i]
      ));
    }

    //ロック解除
    $stmt = $db->prepare('UNLOCK TABLES');
    $stmt->execute();

    $db = null;

    //代引きの場合
    if (isset($_POST['cash']) == true) {
      $_SESSION['pay'] = 'cash';
      header('Location: shop_form_done.php');
      exit();
    }

    //カード払いの場合
    if (isset($_POST['card']) == true) {
      $_SESSION['pay'] = 'card';
      header('Location: shop_card.php');
      exit();
    }
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
      <h1>古本のアルジ</h1>
      <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
    </header>
    <main>
      <h2 class="heading">お客様情報</h2>
      <form action="" method="post">
        <table class="customer-info">
          <tr>
            <td>お名前</td>
            <td>
              <span class="border-bottom">
                <?php echo h($rec2['name']); ?>
              </span>
            </td>
          </tr>
          <br>
          <tr>
            <td>メールアドレス</td>
            <td>
              <span class="border-bottom">
                <?php echo h($rec2['email']); ?>
              </span>
            </td>
          </tr>
          <br>
          <tr>
            <td>郵便番号</td>
            <td>
              <span class="border-bottom">
                <?php echo h($rec2['postal']); ?>
              </span>
            </td>
          </tr>
          <br>
          <tr>
            <td>住所</td>
            <td>
              <span class="border-bottom">
                <?php echo h($rec2['address']); ?>
              </span>
            </td>
          </tr>
          <br>
          <tr>
            <td>電話番号</td>
            <td>
              <span class="border-bottom">
                <?php echo h($rec2['tel']); ?>
              </span>
            </td>
          </tr>
        </table>
        <input class="button black" type="button" onclick="history.back()" value="戻る"> |
        <input class="button black" type="submit" name="cash" value="代引き"> |
        <input class="button black" type="submit" name="card" value="カード払い">
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
