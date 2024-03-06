<?php 
/**
 * обробник для сесії яку ми передаємо у кнопки на сторінці в корені проекту index.php 
 * таким чином ми повідомляємо вже авторизованого користувача що він не може зайти на сторінку 
 * авторизації та реєстрації тому що він вже авторизувався
 * 
 */
session_start();
if(!isset($_SESSION)) {echo 'сесія непрацює';}

$_SESSION['mess_autho_use'] = [
    'autho_vessage' => 'Ви вже зареєстровані, та увійшли у свій акаунт, ви ненможете повторно зареєструватись!',
    'red_message' => 'Bи вже авторизовані, та увійшли у свій акаунта, ви неможете повторно авторизуватись!',
];
if(isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'autho' && isset($_SESSION['mess_autho_use']['autho_vessage'])) {
        $_SESSION['mess_autho_use']['autho_vessage'];
        unset($_SESSION['mess_autho_use']['autho_vessage']); 
        
    } elseif ($action === 'red_message' && isset($_SESSION['mess_autho_use']['red_message'])) {
        $_SESSION['mess_autho_use']['red_message'];
        unset($_SESSION['mess_autho_use']['red_message']);
       
    }
}
header('Location: ../index.php');

