<?php
session_start();
session_regenerate_id();
// ダウンロードしたStripeのPHPライブラリのinit.phpを読み込む
require_once('../vendor/autoload.php');

// APIのシークレットキー
\Stripe\Stripe::setApiKey('
  sk_test_51HKKZoJPLEKFzxmlNjsgzQnUC60yd1eLBu2OmkmHT88q
  3xR3eiRjM0CVrHWAC5EGtHvqcvr0sJNVcXp7Zru5xMMG00k9QcfoKZ
');

$total = $_SESSION['total'];

$token = $_POST['stripeToken'];

try {

    if (!is_string($token)) {
        throw new Exception('文字列以外のトークンが指定されました');
    }

    $charge = \Stripe\Charge::create(array(
        "amount" => $total,
        "currency" => "jpy",
        "source" => $token,
        "description" => "sample01の課金処理"
    ));

    header('Location: shop_form_done.php');
    exit();

} catch (\Stripe\Error\Card $e) {
    echo "ERORR:" . $e->getMessage();
    exit;
}
