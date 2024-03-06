<?php 
/**
 * обробник якй видалє продукт з кошику на сторінці basket-page.php 
 * тут відбувається видалення продукту з таблиці quota та поівернення товару в таблицю  products та тої кількості яка там була
 * 
 */
include_once '../connect.php';

$user_id           = variableValidation($_POST['user_id']);
$product_id        = variableValidation($_POST['product_id']);
$product_quontity  = variableValidation($_POST['product_quontity']);
   
try {
    if (!empty($user_id) && !empty($product_id)) {
        # кількість продукту в кошику перед видаленням
        $sqlQuotaQuantity = "SELECT * FROM quota WHERE user_id = $user_id AND product_id = $product_id";
        $resultQuotaQuantity = mysqli_query($connect, $sqlQuotaQuantity);

        if ($resultQuotaQuantity && mysqli_num_rows($resultQuotaQuantity) > 0) {
            $row = mysqli_fetch_assoc($resultQuotaQuantity);
            $quota_quantity = $row['product_quontity'];
            
            # Видалення  продуктту з кошика
            $sqlDeleteFromQuota = "DELETE FROM quota WHERE user_id = $user_id AND product_id = $product_id";
            $resultDeleteFromQuota = mysqli_query($connect, $sqlDeleteFromQuota);

            if ($resultDeleteFromQuota) {
                # Оновлення кількості продукту в таблиці products і додава кількості яку користувач видалив
                $sqlProductQuantity = "SELECT product_quontity, sales_count FROM products WHERE product_id = $product_id";
                $resultProductQuantity = mysqli_query($connect, $sqlProductQuantity);

                if ($resultProductQuantity && mysqli_num_rows($resultProductQuantity) > 0) {
                    $row = mysqli_fetch_assoc($resultProductQuantity);
                    $currentProductQuantity = $row['product_quontity'];
                    $newProductQuantity = $currentProductQuantity + $quota_quantity;
                    $salesCount = $row['sales_count'];

                    $sqlUpdateProductQuantity = "UPDATE products SET product_quontity = $newProductQuantity, sales_count = sales_count - $salesCount  WHERE product_id = $product_id";
                    $resultUpdateProductQuantity = mysqli_query($connect, $sqlUpdateProductQuantity);

                    if ($resultUpdateProductQuantity) {
                        header('Location: ../pages/basket-page.php');
                    } else {
                        throw new Exception('помилка при оновленні кількості товарів в таблиці продуктів');
                    }
                }
            } else {
                throw new Exception('помилка при видаленні товару з кошика');
            }
        } else {
            throw new Exception('помилка при отриманні продукту з таблиці продуктів');
        }
    } else {
        throw new Exception('помилка отримання id юзера та продукту');
    }
} catch (Exception $e) {
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/delete-product.log");
}

mysqli_close($connect);