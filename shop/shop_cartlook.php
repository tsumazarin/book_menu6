<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if (isset($_SESSION['cus_login']['now']) == true) {
    $login_name = $_SESSION['cus_login']['name'];
  }

  if (isset($_SESSION['carts']) == true) {
    $carts = $_SESSION['carts'];
    $number = $_SESSION['number'];
    $max = count($carts);
    $_SESSION['max'] = $max;
  }else{
    $max = 0;
  }

  //カートに入れた古本を取り出す
  foreach ($carts as $cart) {
    $stmt = $db->prepare('SELECT mp.code,mp.name,mp.price,mp.image
      FROM
        mst_product mp
      WHERE
        code=?
    ');
    $data[0] = $cart;
    $stmt->execute($data);
    $rec = $stmt->fetch();

    $selected_name[] = $rec['name'];
    $selected_price[] = $rec['price'];
    $selected_image[] = $rec['image'];

  }

  //総額を出す
  $total = 0;
  for ($i = 0; $i < $max; $i++) {
    $total += $selected_price[$i] * $number[$i];
  }
  $_SESSION['total'] = $total;


  $db = null;

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本のアルジ</title>
  </head>
  <body>
    <?php if ($max == 0) : ?>
      <p>カートに商品が入っていません</p>
      <a class="button" href="shop_list.php">商品一覧へ</a>
    <?php else : ?>
      <?php if ($_SESSION['cus_login']['now']) : ?>
        <p><?php echo h($login_name); ?>さん、ようこそ</p><br>
        <a class="button logout" href="member_logout.php">ログアウト</a>
        <div class="clear"></div>
      <?php else : ?>
        <p>ゲストさん、ようこそ</p><br>
        <a class="button login" href="member_login.php">会員ログイン</a>
        <div class="clear"></div>
      <?php endif; ?>
      <div class="midashi-wrapper">
        <h2>カートの中身</h2>
        <form class="cart_form" action="number_change.php" method="post">
          <table border="5px solid #D0B0FF">
            <tr>
              <td>タイトル</td>
              <td>表紙</td>
              <td>価格</td>
              <td>個数</td>
              <td>小計</td>
              <td>削除</td>
            </tr>
            <?php for ($i = 0; $i < $max; $i++) : ?>
              <tr>
                <td>『<?php echo h($selected_name[$i]); ?>』</td>
                <td>
                  <img src="../product/pro_picture/<?php echo h($selected_image[$i]); ?>">
                </td>
                <td><?php echo h($selected_price[$i]); ?>円</td>
                <td>
                  <input type="text" name="number<?php echo h($i); ?>" value="<?php echo h($number[$i]); ?>" size="5">コ
                </td>
                <td><?php echo h($selected_price[$i] * $number[$i]); ?>円</td>
                <td>
                  <input type="checkbox" name="delete<?php echo h($i); ?>" value="on">
                </td>
              </tr>
            <?php endfor; ?>
          </table>
          <br><br>
          <div class="change">
            <p>合計：<?php echo h($total); ?>円</p>
            <p>※　個数は10コ以内でお願いします</p><br>
            <input class="button" type="button" onclick="history.back()" value="戻る"> |
            <input class="button" type="submit" value="個数変更">
          </div>
        </form>
      </div>
      <br>
      <a class="button" href="shop_form.php">ご購入手続きへ進む</a>
      <?php if (isset($_SESSION['cus_login']['now']) == true) : ?>
         |
        <a class="button" href="shop_kantan_check.php">
          会員限定かんたん注文へ進む
        </a>
      <?php endif; ?>
    <?php endif; ?>
    <footer></footer>
  </body>
</html>
