<?php 
  /**
   * обробник даних з контактної форми  сторінки авторизації користувача, тут ми отримуємо дані 
   * зі сторінки  pages/authorization-page.php та перевіряємо на валідність введення даних 
   * вже зареєстрованого користувача для подальшого редіректу на сторінку юзера сustomer/index.php
   */
  session_start();
  if(!isset($_SESSION)) { echo 'сесія непрацює';}
  require '../connect.php';
  /**
  * Сесія – це спосіб зберігання інформації (у змінних), яка буде використовуватися на кількох сторінках.
  * cтворити сесію при успішній авторизації  та записати в сесію firstname lastname 
  * додати в сесію firstname lsatname 
  * редірект на сторінку user index.php 
  */
$password = $_POST['password'];
$email = $_POST['email'];

$password = stripslashes($password); 
$password = htmlspecialchars($password);
$email = stripslashes($email); 
$email = htmlspecialchars($email);
$password  = trim($password);

$sqlSelect = "SELECT * FROM  users  WHERE email='$email' and  pass='$password'"; # роблю запит для отримання данах з таблиць для стовпців емелу та пароля
$resultSelect = mysqli_query($connect, $sqlSelect);  # повертає нам обєкт даних( mysqli_result)
$rowsSqli = mysqli_fetch_assoc($resultSelect); # Обидва ці методи дозволяють отримувати рядки результату запиту у вигляді асоціативного або пронумерованого масиву для подальшої обробки даних


try {
  if( $rowsSqli['email'] === $email && $rowsSqli['pass'] === $password ) {
      $_SESSION['profile'] = [
        "firstname" => $rowsSqli['firstname'],
        "lastname" => $rowsSqli['lastname'],
        "user_id" => $rowsSqli['user_id'],
        "email" => $rowsSqli['email'],
        "pass" => $rowsSqli['pass'],
      ];
      header('Location: ../customer/index.php'); 
  } else {
    $_SESSION['buttons'] = [ 
      "change_pass" => "Змінити пароль",
      "registr" => "Реєстрація"
    ];
    $_SESSION['error'] = 'Пароль або Емейл невірний, якщо ви незареєстровані натисніть кнопку \'Реєстрація\', якщо забули пароль натисніть кнопку \'Змінити пароль\'';
    header('Location: ../pages/authorization-page.php');
    throw new Exception('Користувач ввів не вірний пароль або емейл');
  }
  $_SESSION['userEmail'] = $email;
} catch(Exception $e) {
  error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/authorization.log");
}
mysqli_close($connect);


