
<?php 
    session_start();
    if(!isset($_SESSION)) {echo 'сесія непрацює';}
    include('header.php'); 
    include_once 'connect.php';
?>    
<section class="home-page">
        <div class="wrapper-schosse-buttons">
            <div class="item-schosse-buttons">
                    <?php  
                        if(!isset($_SESSION['profile'])) {
                            echo '<a href="pages/registration-page.php">Реєстрація</a>'; 
                            echo '<a href="pages/authorization-page.php">Авторизація</a>'; 
                            echo '<a href="pages/users-page.php">Користувачі</a>';
                        } else {
                            echo '<a href="data_processor/user_access.php?action=red_message">Реєстрація</a>';
                            echo '<a href="data_processor/user_access.php?action=autho">Авторизація</a>'; 
                            echo '<a href="pages/users-page.php">Користувачі</a>';
                            echo '<a href="customer/index.php">Вхід в акаунт</a>';
                        }
                    ?>
            </div> 
            <div class="item-message-access">
                <?php 
                        if(isset($_SESSION['mess_autho_use']['red_message'])) {
                        echo '<span class="item-message">' . $_SESSION['mess_autho_use']['red_message'] . '</span>';
                        unset($_SESSION['mess_autho_use']['red_message']);
                    } elseif(isset($_SESSION['mess_autho_use']['autho_vessage'])) {
                        echo '<span class="item-message">' . $_SESSION['mess_autho_use']['autho_vessage'] . '</span>';
                        unset($_SESSION['mess_autho_use']['autho_vessage']);
                    } 
                ?>
            </div>
        </div>
</section>

<?php mysqli_close($connect); ?>
<? include("footer.php") ?>