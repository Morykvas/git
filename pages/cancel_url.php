<?php
/**
 * сторінка на яку потрапляє користувач якщо y checkcout stripe не пройшла оплата з певних причин
 * на сторінці можна показати повідомлення та причин не пройденої оплати 
 */
session_start();
if (!isset($_SESSION)) {echo 'сесія непрацює';}
define('CSS_DIR', '../');
include_once '../connect.php';
include_once '../header.php';
?>
<section class="basket-page">
    <div class="container">
            <h1>ВАША ОПЛАТА НЕ ПРОЙШЛА</h1>
    </div>
</section>
<?php
mysqli_close($connect);
define('SCRIPT_DIR', '../js/');
include '../footer.php';
?>