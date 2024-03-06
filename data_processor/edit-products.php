<?php
/**
 * обробник у якому ми можемо редагувати продукт натсторінці private-products-page.php 
 */
include '../connect.php';

$product_id   = variableValidation($_POST['product_id']);
$prodName     = variableValidation($_POST['product_name']);
$prodPrice    = variableValidation($_POST['product_price']);
$prodDesc     = variableValidation($_POST['product_description']);
$prodQuan     = variableValidation($_POST['product_quontity']);
$user_id      = variableValidation($_POST['user_id']);
$imageProduct =  $_FILES['file']['tmp_name'];
$imageContent = file_get_contents($imageProduct);
$escapedImageContent = mysqli_real_escape_string($connect, $imageContent);

try {
    
if ($product_id) {

     $updateFields = [];

        if (!empty($prodName)) {
            $updateFields[] = "product_name = '$prodName'";
        }
        if (!empty($prodPrice)) {
            $updateFields[] = "product_price = '$prodPrice'";
        }
        if (!empty($prodDesc)) {
            $updateFields[] = "product_description = '$prodDesc'";
        }
        if (!empty($prodQuan)) {
            $updateFields[] = "product_quontity = '$prodQuan'";
        }
        if (!empty($user_id)) {
            $updateFields[] = "user_id = '$user_id'";
        }
        if (!empty($escapedImageContent)) {
            $updateFields[] = "product_image = '$escapedImageContent'";
        }
        $updateFieldsString = implode(', ', $updateFields);

        if (!empty($updateFields)) {
            $sql = "UPDATE products SET $updateFieldsString WHERE product_id = $product_id";

            $query = mysqli_query($connect, $sql);

            if ($query) {
                validationMessage('Ви успішно внесли зміни у свій продукт');
                header('Location: ../pages/edit-product-page.php');
            } else {
                throw new Exception('Помилка при виконанні запиту для внесення змін у  продук');
            }
        } else {
            invalidMessage('Вкажіть дані продукту які ви хочете оновити ');
            header('Location: ../pages/edit-product-page.php');
            throw new Exception('користувач не заповнив жодного поля для редагування');
        }
    } else {
        throw new Exception('відсутній id продукту для зміни параментрів');
    }
} catch (Exception $e) {
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/edit-products.log");
}

mysqli_close($connect); 