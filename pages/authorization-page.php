<?php
# сторінка з формою для авторизації користувача у свій акаунт попередньо вже зареєстрований

session_start();
if (!isset($_SESSION)) {
    echo 'сесія непрацює';
}
define('CSS_DIR', '../');
include '../header.php';
/**
 * якщо сешин ерор пустий нічого неробити
 * якщо є дані відображати повідомлення і кнопки
 * блок з кнопкою і повідомленням відновити пароль
 * зробити ансет виведених даниї сесії  ерор
 */
?>
<?php if (isset($_SESSION['profile'])) {
    header('Location: user/index.php');
    exit();
} ?>
    <section class="authorization-page">
        <div class="container">
            <div class="item-back-title">
                <a class="button-back" href="../index.php">повернутись</a>
            </div>
            <div class="wrapper-autorization-form">
                <form class="form-authorization"
                      action="../data_processor/autho.php" method="post">
                      <h1 class="title">Авторизація</h1>
                    <input type="email" name="email" placeholder="email">
                    <input type="password" name="password" placeholder="pass">
                    <input class="submit-autho" type="submit" value="submit">
                    <?php
                    echo '<div class="item-buttons">';
                    if (isset($_SESSION['buttons'])) {
                        echo '<a  href="registration-page.php">'
                            .$_SESSION['buttons']['registr'].'</a>';
                        echo '<a href="change-page.php">'
                            .$_SESSION['buttons']['change_pass'].'</a>';
                    }
                    unset($_SESSION['buttons']);
                    echo '</div>';
                    ?>
                    <?php if (isset($_SESSION['error'])) {
                        echo '<p class="item-emassage-error">'
                            .$_SESSION['error'].'</p>';
                    }
                    unset($_SESSION['error']); ?>
                </form>
            </div>
        </div>
    </section>
<?php
define('SCRIPT_DIR', '../js/');
include '../footer.php';
?>