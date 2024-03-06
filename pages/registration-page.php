<?php 
    # сторінка з контактною формою для реєстрації користувача
    
    session_start();
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    define('CSS_DIR', '../');
    include '../header.php';
?>
<?php if(isset($_SESSION['profile'])) {header('Location: user/index.php');exit(); }?>
<section class="registration-page">
    <div class="container">
        <div class="item-back-title">
            <a class="button-back" href="../index.php">повернутись</a>
        </div>
        <div class="wrapper-form-registration">
            <form class="form-registration" action="../data_processor/reg.php" method="post" >
                <h2 class="title">Реєстрація</h2>
                <input type="text"     name="last_name"    placeholder="Прізвище">
                <input type="text"     name="first_name"   placeholder="Імя">
                <input type="text"     name="surname"      placeholder="Побатькові">
                <input type="tel"      name="tel"          placeholder="+380">
                <input type="emil"     name="email"        placeholder="Емейл" >
                <input type="password" name="password"     placeholder="Пароль">
                <input type="password" name="password_two" placeholder="Повторіть пароль">
                <input type="submit"   name="submit"       value="Реєстрація" class="button">
                <?php  
                    if( isset($_SESSION['email_pass'])) {
                        echo '<div class="item-emassage-error">';
                        echo '<span>' .  $_SESSION['email_pass'] . '</span>';
                        echo '</div>';
                    } unset($_SESSION['email_pass']);
                    if(isset($_SESSION['authorization']['author'])) {
                        echo '<div class="item-valid-message">';
                        echo '<span class="registr-message">' . $_SESSION['authorization']['author'] . '</span>';
                        echo '</div>';
                    } unset( $_SESSION['authorization']['author']);
                    if(isset( $_SESSION['psswords'] )) {
                        echo '<div class="item-emassage-error">';
                        echo '<span class="info-pass">' .  $_SESSION['psswords']  . '</span>';
                        echo '</div>';
                    } unset( $_SESSION['psswords'] );
                    if(isset($_SESSION['email_error'])) {
                        echo '<div class="item-emassage-error">';
                        echo '<span class="info-email">' . $_SESSION['email_error'] . '</span>'; 
                        echo '</div>';
                    } unset($_SESSION['email_error']);
                    if(isset( $_SESSION['fields_error'] )) {
                        echo '<div class="item-emassage-error">';
                        echo '<span class="info-email">' . $_SESSION['fields_error'] . '</span>'; 
                        echo '</div>';
                    } unset($_SESSION['fields_error']);
                    if(isset($_SESSION['authorization']['autho_link'])) {
                        echo '<div class="item-button-reg">';
                        echo '<a href="authorization-page.php" class="button-autho-reg">' . $_SESSION['authorization']['autho_link'] . '</a>';
                        echo '</div>';
                    }   unset($_SESSION['authorization']['autho_link']);
                ?>
            </form>
        </div>
    </div>
</section>

<?php 
define('SCRIPT_DIR', '../js/');
include '../footer.php';
?>