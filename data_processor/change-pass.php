<?php
/**
 * обробник даних зі сторінки pages/change-pass.php 
 * на цій сторінці користувач який при спробі авторизації забув пароль, може його змінити 
 * при умові що введений емейл введений вірно в такому випадку він може його змінити 
 */

session_start();
if(!isset($_SESSION)) { echo 'сесія непрацює';}
 
require '../connect.php';
$changePass = $_POST['password'];
$changePassTwo = $_POST['passwordTwo']; 


$changePass = stripslashes($changePass); 
$changePass = htmlspecialchars($changePass);

$changePassTwo = stripslashes($changePassTwo);
$changePassTwo = htmlspecialchars($changePassTwo);

$changePass = trim($changePass);
$changePassTwo = trim($changePassTwo);

$userEmail = $_SESSION['userEmail'];

try {
    if ($changePass !== $changePassTwo) {
        $_SESSION['invalidPass'] = 'Паролі повинні бути однаковими, повторіть спробу!';
        header('Location: ../pages/change-page.php');
        throw  new Exception('користувач ввів не однакові паролі');
    } else {
        $query = "UPDATE users SET pass='$changePass' WHERE email='$userEmail'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $_SESSION['backAutho'] = 'Повернутись';
            $_SESSION['update'] = 'Ви успішно змінили свій пароль, ви можете повернутись на сторінку авторизації натиснкувши на кнопку \'повернутись\'';
            header('Location: ../pages/change-page.php');
        }  
        mysqli_close($connect);
    }
} catch(Exception $e ) {
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/password.log");
}
 

mysqli_close($connect);

?>


 
 







