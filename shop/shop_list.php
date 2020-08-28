<?php
  session_start();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['cus_login']['now'])==true){
    $login_name=$_SESSION['cus_login']['name'];
  }

  //スタッフ追加
  if(isset($_POST['add'])==true){
    header('Location: pro_add.php');
    exit();
  }

  if(!empty($_POST['pro_code'])){
    $_SESSION['product']['code']=$_POST['pro_code'];

    //スタッフ参照
    if(isset($_POST['display'])==true){
      header('Location: pro_display.php');
      exit();
    }

    //スタッフ修正
    if(isset($_POST['edit'])==true){
      header('Location: pro_edit.php');
      exit();
    }

    //スタッフ削除
    if(isset($_POST['delete'])==true){
      header('Location: pro_delete.php');
      exit();
    }
  }

  //古本をすべて取り出す
  $stmt=$db->prepare('SELECT
      mp.code,mp.name,mp.price,mp.image
    FROM
      mst_product mp
    WHERE
      1
  ');
  $stmt->execute();


  $db=null;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css?v=2">
    <title>古本の主人</title>
  </head>
  <body>
    <header>
      <?php if($_SESSION['cus_login']['now']): ?>
        <p class="welcome"><?php echo h($login_name); ?>さん、ようこそ</p><br>
        <a class="button logout" href="member_logout.php">ログアウト</a>
        <div class="clear"></div>
      <?php else: ?>
        <p class="welcome">ゲストさん、ようこそ</p><br>
        <a class="button login" href="member_login.php">会員ログイン</a>
        <div class="clear"></div>
      <?php endif; ?>
    </header>
    <div class="midashi-wrapper">
      <h2>古本一覧</h2>
    </div>
    <?php while(true): ?>
      <?php $rec=$stmt->fetch(); ?>
      <?php if($rec==false){break;} ?>
      <div class="item">
        <a class="item_title" href="shop_product.php?procode=<?php echo h($rec['code']); ?>">
          <img class="item_image" src="../product/pro_picture/<?php echo h($rec['image']); ?>" alt=""><br>
          『<?php echo h($rec['name']); ?>』　
          <?php echo h($rec['price']); ?>円
        </a>
        <br>
      </div>
    <?php endwhile; ?>
    <div class="clear"></div>
    <br>
    <div class="cartlook">
      <a class="cartlook button" href="shop_cartlook.php">カートを見る</a>
    </div>
    <footer></footer>
  </body>
</html>
