<?   
    /**
     * сторінка авторизованого користувача 
     * тут він може переглядати своє листування з користувачами та перейти на сторінку зі списком 
     * користувачів які вже зареєстровані pages/users-page.php
     * також є кнопка для виходу з акаунту яка посилається на файл data_processor/session-exit.php
     */

    session_start();
    if (!isset($_SESSION)) {echo 'сесія неіснує ';} 
    include '../connect.php';
    define('CSS_DIR', '../');   
    include '../header.php';
?>

<section class='user-page'>
    <div class="container">
        <div class="wrapper-button-exit">
            <a href="../data_processor/session-exit.php">Вийти</a>
        </div>
        <div class="wrapper-user-page">
            <div class="item-links">
              <?php  if($_SESSION['profile']) : ?>
                    <?php   echo '<h2>Вітаємо! ' . $_SESSION['profile']['firstname'] . ' ' . $_SESSION['profile']['lastname'] . ' Ви зайшли у свій акаунт</h2>'; ?>
                    <div class="wrapper-links">
                        <a href="../pages/users-page.php">Користувачі</a>
                        <a href="../pages/set-products-page.php">Завантажити продукт</a>
                        <a href="../pages/all-products-page.php">Сторінка продуктів</a>
                        <a href="../pages/private-products-page.php">Мої продукти</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="item-user"> 
            <?php 
                    function countUnreadMessages($connect, $user_id) {
                        $sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE recipient_id='$user_id' AND is_read=0";
                        $result = mysqli_query($connect, $sql);
                        $row = mysqli_fetch_assoc($result);
                        return $row['unread_count'];
                    } 

                    try {   
                        if ($_SESSION['profile']) {
                            $user_id = $_SESSION['profile']['user_id'];
                            $show = isset($_GET['show']) ? $_GET['show'] : '';

                        
                            echo '<div class="buttons">';
                            
                                echo '<a href="?show=all">Всі повідомлення</a>';
                                
                                $unreadCount = countUnreadMessages($connect, $user_id);
                                if ($unreadCount > 0) {
                                    echo '<a href="?show=unread">Непрочитані (' . $unreadCount . ')</a>  ';
                                } else {
                                    echo '<a href="?show=unread">Непрочитані</a>';
                                }
                                echo '<a href="?show=sent">Відправлені</a>';

                            

                            echo '</div>';
                            
                            if ($show == 'unread') {
                                echo '<div class="item_users_messagas-recipient">';
                                    echo '<p>Непрочитаних повідомлень немає</p>';
                                    $sqlReceivedMessages = "SELECT * FROM messages WHERE recipient_id='$user_id' AND is_read=0";
                                    $unreadMessagesResult = mysqli_query($connect, $sqlReceivedMessages);
                                    while ($row = mysqli_fetch_assoc($unreadMessagesResult)) {
                                        echo '<pre>';
                                            echo 'Відправник: ' . $row['sender_id'] . '<br>';
                                            echo 'Повідомлення: ' . $row['message_text'] . '<br>';
                                        echo '</pre>';
                                    }
                                echo '</div>';
                                
                                # Позначаємо непрочитані повідомлення як прочитані
                                $sqlMarkAsRead = "UPDATE messages SET is_read = 1 WHERE recipient_id = '$user_id' AND is_read = 0";
                                mysqli_query($connect, $sqlMarkAsRead);
                                
                                # Оновлюємо лічильник непрочитаних повідомлень
                                $unreadCount = countUnreadMessages($connect, $user_id);
                            } elseif ($show == 'sent') {
                                echo '<div class="item_users_messagas-recipient">';
                                    echo '<p>Відправлені повідомлення</p>';
                                $sqlSentMessages = "SELECT * FROM messages WHERE sender_id='$user_id'";
                                $sentMessagesResult = mysqli_query($connect, $sqlSentMessages);
                                while ($row = mysqli_fetch_assoc($sentMessagesResult)) {
                                    echo '<pre>';
                                        echo 'Отримувач: ' . $row['recipient_id'] . '<br>';
                                        echo 'Повідомлення: ' . $row['message_text'] . '<br>';
                                    echo '</pre>';
                                }
                                echo '</div>';
                            } elseif ($show == 'all') {
                                $sqlAllMessages = "SELECT * FROM messages WHERE recipient_id='$user_id' OR sender_id='$user_id'";
                                $allMessagesResult = mysqli_query($connect, $sqlAllMessages);
                                echo '<div class="item_users_messagas-recipient">';
                                    echo '<p>Всі повідомлення<p>';
                                    echo '<pre>';
                                    while ($row = mysqli_fetch_assoc($allMessagesResult)) {
                                        if ($row['sender_id'] == $user_id) {  
                                                echo 'Відправлені:  ' . $row['recipient_id']. '<br>';
                                                } else {
                                                echo 'Отримані: ' .  $row['sender_id'] .  '<br>';
                                        }
                                        echo 'Повідомлення: ' . $row['message_text'] . '<br>';   
                                    } 
                                    echo '</pre>';
                                echo '</div>';
                            } 
                        } else {
                            echo '<h1>' . 'Ви зайшли на закриту сторінку'. '<br>' .' Ідіть нахуй по харошому!' . '</h1>';
                            throw new Exception('спроба входу не авторизованого користувача!');
                        }
                    } catch(Exception $e ) {
                        error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/profile.log");
                    }  
                    ?>
                 </div>
            </div>
        </div>
    </div>
</section> 
<?
 mysqli_close($connect);
 include '../footer.php'; 
 ?>



