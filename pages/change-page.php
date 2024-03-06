<?php 
    # сторінка з формою для зміни паролю, якщо користувач не зміг авторизуватись через забутий пароль

    session_start();
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    define('CSS_DIR', '../');
    include '../header.php'; 
?>

<?php 
if(isset($_SESSION['profile'])) {header('Location: ../customer/index.php');exit(); }
?>
<section class="change-page">
    <div class="container">
        <div class="item-back-title">
            <a class="button-back" href="../index.php">повернутись</a>
        </div>
        <div class="wrapper-change-pass">
            <form action="../data_processor/change-pass.php" method="post">
                <h3 class="title">Змініть пароль</h3>
                <input type="password" name="password" placeholder="Пароль">
                <input type="password" name="passwordTwo" placeholder="Повторно введіть">
                <input class="button-change" type="submit" name="sibmit" value="Змінити">
                <?php if(isset($_SESSION['backAutho'])) {echo '<a  href="authorization-page.php"class="button_back">' . $_SESSION['backAutho'] . '</a>';}unset($_SESSION['backAutho']);?>
                <?php if(isset($_SESSION['update'])) {echo '<span class="item-valid-message">' . $_SESSION['update'] . '</span>';}unset($_SESSION['update']);?>
                <?php if(isset($_SESSION['invalidPass'])) {echo '<span class="item-emassage-error">' . $_SESSION['invalidPass']  .  '</span>';}unset($_SESSION['invalidPass'] );?>
            </form>
        </div>
    </div>
</section>

<?php
  define('SCRIPT_DIR', '../js/');
  include '../footer.php';
?>