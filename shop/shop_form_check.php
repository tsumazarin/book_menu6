<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $name = $_SESSION['customer']['name'];
  $email = $_SESSION['customer']['email'];
  $postal = $_SESSION['customer']['postal'];
  $address = $_SESSION['customer']['address'];
  $tel = $_SESSION['customer']['tel'];
  $order = $_SESSION['customer']['order'];
  $pass = $_SESSION['customer']['pass'];
  $gender = $_SESSION['customer']['gender'];
  $birth = $_SESSION['customer']['birth'];

  $carts = $_SESSION['carts'];
  $number = $_SESSION['number'];
  $price = $_SESSION['cart_price'];
  $max = count($carts);

  if (isset($_POST['card']) == true || isset($_POST['cash']) == true) {
    //占有ロック開始
    $stmt = $db->prepare('LOCK TABLES
      dat_sales WRITE,
      dat_sales_product WRITE,
      dat_member WRITE
    ');
    $stmt->execute();

    $last_member_code = 0;

    //会員登録情報をデータベス（dat_member）へ
    if ($order == 'order_register') {
      $stmt = $db->prepare('INSERT INTO
        dat_member(
          password,
          name,
          email,
          postal,
          address,
          tel,
          gender,
          birth
        )
        VALUES
          (?,?,?,?,?,?,?,?)
      ');
      $stmt->execute(array(
        $pass,
        $name,
        $email,
        $postal,
        $address,
        $tel,
        $gender,
        $birth
      ));

      //購入履歴IDの最後を取り出す
      $stmt = $db->prepare('SELECT LAST_INSERT_ID()');
      $stmt->execute();
      $rec = $stmt->fetch();
      $last_member_code = $rec['LAST_INSERT_ID()'];
    }

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
      $last_member_code,
      $name,
      $email,
      $postal,
      $address,
      $tel
    ));

    //dat_sales_productにデータを入れる
    $stmt = $db->prepare('SELECT LAST_INSERT_ID()');
    $stmt->execute();
    $rec = $stmt->fetch();
    $last_code = $rec['LAST_INSERT_ID()'];

    for ($i = 0; $i < $max; $i++) {
      $stmt = $db->prepare('INSERT INTO dat_sales_product(
          code_sales,
          code_product,
          price,
          quantity
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
      <h1>古本のアルジ</h1><br>
      <section>　〜品質そこそこ 古本販売サイト〜</section>
    </header>
    <main>
      <h2 class="heading">お客様情報</h2>
      <form action="" method="post">
        <table class="customer-form-check">
          <tr>
            <td>お名前</td>
            <td>
              <span class="border-bottom">
                <?php echo h($name); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>メールアドレス</td>
            <td>
              <span class="border-bottom">
                <?php echo h($email); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>郵便番号</td>
            <td>
              <span class="border-bottom">
                <?php echo h($postal); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>住所</td>
            <td>
              <span class="border-bottom">
                <?php echo h($address); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td>電話番号</td>
            <td>
              <span class="border-bottom">
                <?php echo h($tel); ?>
              </span>
            </td>
          </tr>
          <?php if (isset($_SESSION['customer']['pass']) == true) : ?>
            <tr>
              <td>パスワード</td>
              <td>
                <span class="border-bottom">
                  【表示されません】
                </span>
              </td>
            </tr>
            <tr>
              <td>性別</td>
              <td>
                <?php if ($gender == 1) : ?>
                  <span class="border-bottom">男性</span>
                <?php else : ?>
                  <span class="border-bottom">女性</span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td>生まれ年</td>
              <td>
                <span class="border-bottom">
                  <?php echo h($birth); ?>年代
                </span>
              </td>
            </tr>
          <?php endif; ?>
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
