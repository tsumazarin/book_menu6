<?php
  session_start();
  session_regenerate_id();

  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $max = $_SESSION['max'];
  $carts = $_SESSION['carts'];

  for ($i = 0; $i < $max; $i++) {
    $number[] = mb_convert_kana($_POST["number{$i}"], 'n', 'utf8');
  }

  foreach ($number as $string) {
    if (!is_numeric($string)) {
      header('Location: shop_cartlook.php');
      exit();
    }

    if ($string < 1 || 10 < $string) {
      header('Location: shop_cartlook.php');
      exit();
    }
  }

  for ($i = $max; 0 <= $i; $i--) {
    if ($_POST["delete{$i}"] == 'on') {
      array_splice($carts, $i, 1);
      array_splice($number, $i, 1);
    }
  }

  $_SESSION['carts'] = $carts;
  $_SESSION['number'] = $number;

  header('Location: shop_cartlook.php');
  exit();
?>
