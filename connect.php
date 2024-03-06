<?php 
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 'On');
$serverHost = 'localhost';
$userHost = 'root';
$passwordHost = 'root';
$dbName = 'project';

try {
    $connect = mysqli_connect($serverHost, $userHost, $passwordHost , $dbName);
    if (!$connect) {
        throw new Exception('Помилка підключення до бази даних: ' . mysqli_connect_error());
    }
} catch (Exception $e) {
    error_log($e->getMessage() . PHP_EOL, 3, "var/log/connect.log");
}   

function variableValidation($variable){
    return htmlspecialchars(stripslashes(trim($variable)));
}
function invalidMessage($message) {
   return $_SESSION['invalidMessage'] = $message;
}
function validationMessage($message) {
   return $_SESSION['validationMessage'] = $message;
}

function sessionMessageInvalid($value) {
    if(isset($_SESSION[$value])) {
        echo '<p class="item-emassage-error">' . $_SESSION[$value] . '</p>';
    } unset($_SESSION[$value]); 
}
function sessionMessageValid($value) {
    if(isset($_SESSION[$value])) {
        echo '<p class="item-valid-message">' . $_SESSION[$value] . '</p>';
    } unset($_SESSION[$value]);
}