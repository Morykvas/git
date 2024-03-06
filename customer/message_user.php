<?php 
    /**
     * форма для написання повідомлення для юзера 
     * ми отримуємо get параметр який передає нам id юзера якому ми будемо писати повідомлення 
     * id ми отримуємо зі сторінки pages/users-page.php
     * таким чином передається в значення id користувача прихованому інпуту
     */

    session_start();
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    define('CSS_DIR', '../');
    include '../header.php'; 
    include '../connect.php';
?>
   
<section class="user-message-page">
        <div class="container">
            <div class="button-back">
                <a href="../pages/users-page.php">повернутись</a>
            </div>
            <div class="wraper-user-message-page">
                <?php
                    if (isset($_SESSION['profile'])) {
                        if (isset($_GET['user_id'])) {
                            $recipient_id = $_GET['user_id']; # отримуємо іd користувача якому будемо писати повідомлення
                            $_SESSION['recipient'] = $recipient_id; # передаємо в сесію користувача отримувача
                             # Отримання інформації про користувача
                            $sql = "SELECT * FROM users WHERE user_id = $recipient_id";
                            $result = mysqli_query($connect, $sql);
                            $user = mysqli_fetch_assoc($result);
                        } 
                    } 
                    
                 ?>
                <form class="form_for_message_user" method="post" action="../data_processor/send_message.php">
                    <h2 class="title">Повідомлення для користувача</h2>
                    <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                    <textarea class="message_user_textarea" name="message_for_user" placeholder="Повідомлення" ></textarea>
                    <input type="submit" value="Відправити">
                    <?php  
                        if(isset($_SESSION['send_message_valid'])) {
                            echo '<div class="item-valid-message">';
                            echo '<span >' . $_SESSION['send_message_valid'] . '</span>';
                            echo '</div>';
                        }
                        unset($_SESSION['send_message_valid']);
                        if(isset($_SESSION['send_message_invalid'])) {
                            echo '<div class="item-emassage-error">';
                            echo '<span >' . $_SESSION['send_message_invalid'] . '</span>';
                            echo '</div>';
                        }
                        unset($_SESSION['send_message_invalid']);
                      
                    ?>
                    </form>
                </div>
        </div>
</section>
<?php include '../footer.php'; ?>
