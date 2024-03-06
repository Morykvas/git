<?php
/**
 * обробиник форми для повідомдення користувачеві з customer/message_user.php
 */

session_start();
if(!isset($_SESSION)) {echo 'сесія непрацює';}
require '../connect.php'; 

$sender_id = $_SESSION['profile']['user_id'];
$recipient_id = $_SESSION['recipient'];
$message_text = $_POST['message_for_user'];

$message_text = stripslashes($message_text);
$message_text = htmlspecialchars($message_text);
$message_text = trim($message_text);

if (isset($_SESSION['profile'])) {

    // Перевірте, чи поле message_text не є порожнім
    if (!empty($message_text)) {
        // Використовуйте підготовлений запит
        $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, timestamp) VALUES ('$sender_id', '$recipient_id', '$message_text', NOW())";
    

        if (mysqli_query($connect, $sql)) {
            $_SESSION['send_message_valid'] = 'Ваше повідомлення відправлене користувачу';
            header('Location: ../customer/message_user.php');
        } else {
            echo 'Помилка: ' . mysqli_error($connect);
        }

    } else {
        $_SESSION['send_message_invalid'] = 'Ви не можете відправити повідомлення, заповність спочатку поле для повідомлення';
        header('Location: ../customer/message_user.php');
    }
} else {
    echo 'Невдалий запит';
}












mysqli_close($connect);



