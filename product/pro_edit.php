<?php
  session_start();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  //ログイン確認
  session_regenerate_id(true);
  if(isset($_SESSION['login']['now'])==false){
    header('Location: ../staff_login/staff_login.php');
    exit();
  }

  $login_name=$_SESSION['login']['name'];
  $login_code=$_SESSION['login']['code'];

  $code=$_SESSION['product']['code'];

  //選択された古本を取り出す
  $stmt=$db->prepare('SELECT mp.name,mp.price,mp.image FROM mst_product mp WHERE code=?');
  $stmt->execute(array($code));
  $rec=$stmt->fetch();

  $_SESSION['product']['old_image']=$rec['image'];

  $db=null;

  //「確認」送信
  if(isset($_POST['check'])==true){
    if($_POST['name']==''){
      $error['name']='blank';
    }

    $price=mb_convert_kana($_POST['price'], 'n', 'utf8');
    if($_POST['price']==''){
      $error['price']='blank';
    }else if(is_numeric($price)==false){
      $error['price']='wrong';
    }else if($price<10 || 10000<$price){
      $error['price']='size';
    }

    $image=$_FILES['image'];
    $ext=substr($image['name'], -3);
    if($image['size']<=0){
      $error['image']='blank';
    }else if($image['size']>10000000){
      $error['image']='size';
    }else if($ext!='PEG' && $ext!='peg' && $ext!='png' && $ext!='PNG' && $ext!='jpg' && $ext!='JPG'){
      $error['image']='type';
    }

    //エラーなし
    if(empty($error)){
      $_SESSION['product']['name']=$_POST['name'];
      $_SESSION['product']['price']=$price;
      $_SESSION['product']['image']=$image;

      //画像をアップロード
      move_uploaded_file($image['tmp_name'], "./pro_picture/{$image['name']}");

      header('Location: pro_edit_check.php');
      exit();
    }
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
      <h2>古本修正</h2>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
      <dl>
        <dt class="input_title">古本コード：<?php echo h($code); ?></dt>
        <dd></dd>
        <br>
        <dt class="input_title">タイトル</dt>
        <dd>
          <input class="input_content" type="text" name="name" size="35" value="<?php echo h($rec['name']); ?>">
          <?php if($error['name']=='blank'): ?>
            <p>※　タイトルを記入してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">価格を再設定してください</dt>
        <dd>
          <input class="input_content" type="text" name="price" size="15" value="<?php echo h($rec['price']); ?>">円
          <?php if($error['price']=='blank'): ?>
            <p>※　価格を記入してください</p>
          <?php endif; ?>
          <?php if($error['price']=='wrong'): ?>
            <p>※　数字で記入してください</p>
          <?php endif; ?>
          <?php if($error['price']=='size'): ?>
            <p>※　10円以上10000円以内で設定してください</p>
          <?php endif; ?>
        </dd>
        <br>
        <dt class="input_title">
          写真：
          <input type="file" name="image">
          <?php if($error['image']=='blank'): ?>
            <p>※　画像を改めて設定してください</p>
          <?php endif; ?>
          <?php if($error['image']=='size'): ?>
            <p>※　画像が大きすぎます</p>
          <?php endif; ?>
          <?php if($error['image']=='type'): ?>
            <p>※　「JPEG」「PNG」「JPG」「jpeg」「png」「jpg」いずれかの拡張子で指定してください</p>
          <?php endif; ?>
        </dt>
        <dd></dd>
      </dl>
      <br><br>
      <div class=" menu">
        <input class="button" type="button" onclick="history.back()" value="戻る"> |
        <input class="button" type="submit" name="check" value="確認">
      </div>
    </form>
  </body>
</html>
