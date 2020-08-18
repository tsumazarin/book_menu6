<?php
  //データベース接続
    try{
      $db=new PDO('mysql:dbname=book;host=localhost;charset=utf8', 'root', 'root');
    }catch(PDOException $e){
      echo "DB接続エラー：{$e->getMessage()}";
    }
?>
