
<?php 
 /**
  * обробник контактної форми для реєстрації користувача зі сторінки pages/registration-page.php
  */

session_start();
if(!isset($_SESSION)) { echo 'сесія непрацює'; }
// require_once '../connect.php';
require_once '../connectdb.php';

$userRole = '1';

$first_name =  $_POST['first_name']; 
$last_name = $_POST['last_name'];
$surname =  $_POST['surname'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordTwo = $_POST['password_two'];
$submit = $_POST['submit'];


$first_name = stripslashes($first_name); #прибирає бек слеші з симоволів
$first_name = htmlspecialchars($first_name); #перетворює спеціальні сутності HTML назад на символи

$last_name = stripslashes($last_name);
$last_name = htmlspecialchars($last_name);

$surname = stripslashes($surname);
$surname = htmlspecialchars($surname);

$tel = stripslashes($tel);
$tel = htmlspecialchars($tel);

$email = stripslashes($email);
$email = htmlspecialchars($email);


$password = stripslashes($password);
$password = htmlspecialchars($password);

$passwordTwo = stripslashes($passwordTwo);
$passwordTwo = htmlspecialchars($passwordTwo);

$first_name = trim($first_name);
$last_name  = trim($last_name);
$surname  = trim($surname);
$tel  = trim($tel);
// $email  = trim($temai);
$password  = trim($password);
$passwordTwo  = trim($passwordTwo);



$_SESSION['firstname'] = $_POST['first_name']; 
$_SESSION['lastname'] = $_POST['last_name']; 
$_SESSION['surname'] = $_POST['surname'];
$_SESSION['tel'] = $_POST['tel'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['password'] =  $_POST['password'];

  

#додати перевірку - якщо такий емейл існує в базі не виконувати inser запит    (редірект на сторінку авторизації) 

#Виконуємо SELECT запит, щоб перевірити наявність електронної пошти
$checkQuery = "SELECT COUNT(*) as count FROM users WHERE email = '$email'"; 
/** COUNT(*) підраховує всі рядки які є, підраховує кожеий рядок окремо і їхню загальну кількість разом з тими що повторюються, 
 * навіть ті що мають значення NULL 
 * as count це місце де передається підрахунок всіх рядків які нам потрібно у нашому випадку вказано таблицію та емейл
 * 
*/ 

$result = mysqli_query($connect, $checkQuery); # повертає нам обєкт даних( mysqli_result )
$row = mysqli_fetch_assoc($result);  # виймає результуючиі дані у вигляді асоціативного ряду ( перетворюємо обєкт в асоціативний масив )
$count = $row['count'];
$messageEmailError = 'Емейл такого користувача вже існує, переконайтесь що ви ввели вірний емейл та спробуйте ще раз!';
$errorMessage = 'Паролі незбігаються, повторіть спробу!';
$messSucces = 'Ви успішно зареєструвались, для переходу на сторінку авторизації натисніть кнопку \'Авторизуватись\'';
$errorMessagePass = 'Подібний емел вже зареєстрований. Ведені паролі незбігаються, спробуйте ввести дані ще раз!';


try { 
    if (empty($first_name) || empty($last_name) || empty($surname) || empty($tel) || empty($email) || empty($password) || empty($passwordTwo)) {
        # Якщо хоча б одне з полів порожнє, виводимо повідомлення про пусті поля
        $_SESSION['fields_error'] = "Всі поля обов'язкові для заповнення!";
        header('Location: ../pages/registration-page.php');
        throw new Exception('користувач залишив не заповнені якісь поля реєстрації');
    }  elseif ($count > 0 && $password != $passwordTwo ) {
        #Значення існує в базі даних і паролі не збігаються
        $_SESSION['email_pass'] = $errorMessagePass;
        header('Location: ../pages/registration-page.php');
        throw new Exception('користувач ввів вже зареєстрований емейл, та паролі які не збігаються');
    } elseif ($count > 0) {
        #Значення існує в базі даних, виводимо повідомлення про існуючий емейл
        $_SESSION['email_error'] = $messageEmailError;  
        header('Location: ../pages/registration-page.php');
        throw new Exception('користувач ввів вже зареєстрований Email');
    } elseif ($password != $passwordTwo) {
        #Паролі не збігаються, виводимо повідомлення про незбіг паролів
        $_SESSION['psswords'] = $errorMessage;
        header('Location: ../pages/registration-page.php');
        throw new Exception( 'користувач ввів паролі які не збігаються');
    } else {
        
        #Значення не існує в базі даних і паролі збігаються, виконуємо INSERT запит
        $sql = "INSERT INTO `users`(`firstname`, `lastname`, `surname`, `tel`, `email`, `pass`) VALUES ('$first_name', '$last_name', '$surname', '$tel', '$email', '$password')";
        $sqlInsert = mysqli_query($connect, $sql);
        if ($sqlInsert) {
            $user_id = mysqli_insert_id($connect); # повертає індефікатор останнього запиту
            # Додавання ролі 'відвідувач' для нового користувача
            $role_name = "відвідувач";
            $sqlInsertRole = "INSERT INTO roles (user_id, role_name) VALUES ('$user_id', '$role_name')";
            $resultInsertRole = mysqli_query($connect, $sqlInsertRole);
            if(!$resultInsertRole) {
                throw new Exception('Помилка з\'єднання з базою даних при  додавання ролі');
            } 
            $_SESSION['authorization'] = [
                "author" => $messSucces,  
                "autho_link" => 'Авторизуватись',
            ];
            header('Location: ../pages/registration-page.php');
            } else {
            throw new Exception('Помилка зєднання з базою даних:');
        }
    }
} catch(Exception $e)   { 
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/registration.log");
}

#Закриваємо з'єднання з базою даних
mysqli_close($connect);

