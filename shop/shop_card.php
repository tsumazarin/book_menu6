<?php
  session_start();
  session_regenerate_id();
  require('../htmlspecialchars.php');
  require('../dbconnect.php');

  $total = $_SESSION['total'];
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
    </header>
    <main>
      <form action="charge.php" method="POST">
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="pk_test_51HKKZoJPLEKFzxmlfAECR7GVfOAtbcvivD6onE5LFbwu36ZYrJYakHwxrUeAMagjNkF4UnbUi5A185ho6oYEncwt00UmSvjMhT"
          data-amount="<?php echo h($total); ?>"
          data-name="この商品の料金は<?php echo h($total); ?>円です"
          data-locale="auto"
          data-allow-remember-me="false"
          data-label="クレジット決済する"
          data-currency="jpy">
        </script>
      </form>
    </main>
    <footer>
      <div class="footer-content">
        ---Old Books Sales---
      </div>
    </footer>
  </body>
</html>
