<?php
/**
 * обробробник де ми передаємо категорію яку було обрано 
 * для того щоб передати на сторінку products-categories-page.php та вивести продукти обраної категорії
 */
session_start();
if(!isset($_SESSION)) {'сесія не працює';}
include_once '../connect.php';

 echo $category = variableValidation($_POST['order_category']);
try {
    if($category) {
        $sqlCategory = "SELECT category_name FROM categories WHERE category_id = '$category'";
        $resultCategory = mysqli_query($connect, $sqlCategory);

    
        while($row = mysqli_fetch_assoc($resultCategory)){
            $_SESSION['category_data'] = [
                'category_name' => $row['category_name'],
                'category_id'   => $category
            ];
        }
       
        header('Location: ../pages/products-categories-page.php');
    } else {
       throw new Exception("категорія категорію не знайде або помилка зєднання " . mysqli_error($connect));
    }
} catch(Exception $e) {
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/select_category.log");
}
mysqli_close($connect);