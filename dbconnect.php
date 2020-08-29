<?php
  //データベース接続
    try {
      $dbi = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
  $dbi['dbname'] = ltrim($dbi['path'], '/');
  $dsn = "mysql:host={$dbi['host']};dbname={$dbi['dbname']};charset=utf8";
  $user = $dbi['user'];
  $password = $dbi['pass'];
  $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
  );
  $db = new PDO($dsn,$user,$password,$options);
    }catch (PDOException $e) {
      echo "DB接続エラー：{$e->getMessage()}";
    }
?>
