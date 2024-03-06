<?php 
/**
 * файли для обробки даних при передачі з кошика до оформлення 
 * 
 */
session_start();
if(!isset($_SESSION)) { echo 'сесія непрацює';}
include_once '../connect.php';

$buyQuontity = variableValidation($_POST['buy_quontity']);
$productId   = variableValidation($_POST['product_id']);
$userId      = variableValidation($_POST['user_id']);

try {
    if ($buyQuontity && $productId && $userId) {
        header('Location: ../pages/basket-page.php');
         # отримую поточну кількість продукту в таблиці products
        $sqlSelectProduct = "SELECT product_quontity FROM products WHERE product_id = $productId";
        $resultSelectProducts = mysqli_query($connect, $sqlSelectProduct);

        if ($row = mysqli_fetch_assoc($resultSelectProducts)) {
            $currentQuontity = $row['product_quontity'];
            
            # отримаю поточну кількість продукту в таблиці quota
            $sqlSelectQuota = "SELECT product_quontity FROM quota WHERE user_id = $userId AND product_id = $productId";
            $resultSelectQuota = mysqli_query($connect, $sqlSelectQuota);

            if ($row = mysqli_fetch_assoc($resultSelectQuota)) {
                $currentQuotaQuontity = $row['product_quontity'];
            } else {
                throw new Exception('Не вдалося отримати дані кількості продукту з таблиці quota');
            }

            # Розрахунок різниці між замовленою кількістю і поточною кількістю
            $difference = $buyQuontity - $currentQuotaQuontity;
            $positiveDifference = abs($difference);
            $newProductQuontity = $currentQuontity - $positiveDifference;
          
            $sqlUpdateProducts = "UPDATE products SET product_quontity = $newProductQuontity, sales_count = sales_count  + $positiveDifference WHERE product_id = $productId";

            $resultUpdateProducts = mysqli_query($connect, $sqlUpdateProducts);
            if ($resultUpdateProducts) {

                    $sqlUpdateQuota = "UPDATE quota SET product_quontity = CASE WHEN $buyQuontity > product_quontity THEN product_quontity + $buyQuontity - product_quontity WHEN $buyQuontity < product_quontity THEN product_quontity - (product_quontity - $buyQuontity) ELSE product_quontity END WHERE user_id = $userId AND product_id = $productId";
                    $resultUpdateQuota = mysqli_query($connect, $sqlUpdateQuota);

                if (!$resultUpdateQuota) {
                    throw new Exception('Не вдалося оновити таблицю quota');
                }
            } else {
                throw new Exception('Кількість товару не була оновлена в products');
            }
        } else {
            throw new Exception('Не вдалося отримати дані кількості продукту з таблиці products');
        }
    }
} catch (Exception $e) {
    error_log("Файл: " . $e->getFile() . "  Рядок: " . $e->getLine() . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/buy-products.log");
}

mysqli_close($connect);