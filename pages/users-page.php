<?php 
    /**
    *   Cторінка де є список користувачів які зареєструвались 
    *   Вибраному користувачу можна написати повідомлення у випадку якщо ви авторизувались у свій акаунт
    */
    session_start(); 
    if(!isset($_SESSION)) {echo 'сесія не працює';} 
    define('CSS_DIR','../');
    include '../header.php'; 
    require '../connect.php'; 

?>
<section class="users-page-data">
    <div class="container">
        <div class="item-back-title">
                <?php  if(!isset($_SESSION['profile'])) {
                    echo '<a class="button-back" href="../index.php">повенрутись</a>';
                } else {
                  echo '<a class="button-back" href="../customer/index.php">повенрутись</a>';
                }
                
                ?>
        </div>
        <div class="wrapper-users-data">
            <h1 class="title">Cписок зареєстрованих користувачів</h1>
            <?php
                /**
                 * роблю вибірку з таблиці юзерів
                 * потім з сесії профіля роблю вибірку щоб поточного юзера непоказувати
                 */
                if(isset($_SESSION['profile'])) {
                    $currentUserId = $_SESSION['profile']['user_id'];
                    $sql = "SELECT * FROM users WHERE NOT user_id=$currentUserId";
                } else {
                    $sql = "SELECT * FROM users";
                }
                ?>
            <div class="item-users-data">
                <?php if($result = mysqli_query($connect, $sql)) : ?>
                    <?php while($row = mysqli_fetch_array($result)) : ?>
                        <div class="item-users">
                            <div class="item-data-users">
                            <span><?= 'ID:  '. $row['user_id'];?></span> 
                            <span><?= $row['firstname'];?></span>
                            <span><?= $row['lastname']. ' ,';?></span>
                            <span><?= $row['email']; ?></span>  
                            </div>
                           <?php
                            /**
                            * посилання на сторінку де пожна написати користувачу
                            * в атрибут href передаю посилання на сторінку та параметр id юзера
                            */
                           ?>
                            <?php
                                if(isset($_SESSION['profile'])) 
                                { 
                                    $_SESSION['recipient_id'] = $row['user_id'];
                                    echo '<a href="../customer/message_user.php?user_id=' .  $_SESSION['recipient_id'] .'">' . "Написати" . '</a>';
                                } 
                            ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>            
            </div>
        </div>
    </div>
</section>
<?php 
    mysqli_close($connect);
    define('SCRIPT_DIR', '../js/');
    include '../footer.php';
?>
