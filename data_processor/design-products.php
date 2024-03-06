<?php
/**
 * обробник зі сторінки design-products-page.php де покупець вводить свої дані для оформлення замовлення, записуються в таблиці orders та orders_item
 * тут обробляється дві платіжні сисетеми liqPay та Stripe 
 * взалежності від потреби використовується одина платіжна система
 * в liqPay передаються платіжні днаі через кастомну форму а в stripe редіректить на checkout платежу
 * 
 * 
 */

session_start();
if(!isset($_SESSION)) {'сесія не працює';}
include_once "../connect.php";
require_once('../vendor/autoload.php');



$firstName = variableValidation($_POST['first_name']);
$lastName  = variableValidation($_POST['last_name']);
$surname   = variableValidation($_POST['surname']);
$userTel   = variableValidation($_POST['user_tel']);
$placeDelivery = variableValidation($_POST['is_order']);
$region    = variableValidation($_POST['select-region']);
$city      = variableValidation($_POST['select-sity']);
$delivery  = variableValidation($_POST['select-place-delivery']);

$curdNumber = variableValidation($_POST['card-number']);
$expMonth   = variableValidation($_POST['expmonth']);
$expYear    = variableValidation($_POST['expyear']);
$cvv        = variableValidation($_POST['cvv']);

$user_id   = $_SESSION['profile']['user_id'];
$dataJson  = $_POST['json_data'];
$data      = json_decode($dataJson, true);


try { 
    
      if( $placeDelivery && $firstName && $lastName && $surname && $userTel && $region && $city && $delivery && $curdNumber && $expMonth && $expYear && $cvv) {  
        // validationMessage('Ви оплатили свою покупку');
        header('Location: ../pages/design-products-page.php');
        
        $deliveryValue = '';
        if($placeDelivery  == 'one') {
            $deliveryValue = 'Відділення';
        } elseif($placeDelivery  == 'two') {
            $deliveryValue = 'Адресна доставка';
        }
        $totalPrice = 0;
        foreach ($data as $values ) {
           $totalPrice += $values['product_price'];
        }

        # записую дані в таблицю Oreders там дані про місце достваки та користувача який замовив та сума
        $sql    = "INSERT INTO orders (user_id, order_date, total_price, first_name, last_name, surname, phone_number, region, city, department, place_delivery) VALUES ('$user_id', NOW(), '$totalPrice','$firstName', '$lastName', '$surname', '$userTel', '$region', '$city', '$delivery','$deliveryValue')";
        $result = mysqli_query($connect, $sql);

        if($result) {
            
            # пісял того отримаую дані з таблиці orders для того щоб передати їх в оплату як опис оплати
            $sqlSelect    = "SELECT * FROM orders WHERE user_id = $user_id";
            $resultSelect = mysqli_query($connect, $sqlSelect);

            
            while($rowSelect = mysqli_fetch_assoc($resultSelect)) {
                $orderId = $rowSelect['order_id'];
            }


            // $public_key  = 'sandbox_i28301361400';
            // $private_key = 'sandbox_BcDUuuhdOFfSxu9V0FYtccSBA8YTykXRTbn0wvi0';

            // $liqpay = new LiqPay($public_key, $private_key);
            // return   $res = $liqpay->api("request", array(
            //     'action'         => 'pay',
            //     'version'        => '3',
            //     'phone'          => $userTel,
            //     'amount'         => $totalPrice,
            //     'currency'       => 'UAH',
            //     'order_id'       =>  $orderId, 
            //     'description'    => 'LiqPay',
            //     'card'           => $curdNumber,
            //     'card_exp_month' => $expMonth,
            //     'card_exp_year'  => $expYear,
            //     'card_cvv'       => $cvv,
            // )); 
            


                try {

                    $stripe = new \Stripe\StripeClient('sk_test_51OBw0AJcybMk0KW9lOsgPfCJncbtkq485yWE6zyn7lhdtddyLKOkEC1NRXMgC3QNvwuqJVuPvLoaP9SOrRqhc6QA00bUImX4VB');
                    $product = $stripe->products->create([
                        'name' => 'Сума вашого замовлення',
                        'description' => 'Інтернет покупка',
                    ]);
                                
                    $setPrice =  $totalPrice * 100;
                    $price = $stripe->prices->create([
                        'unit_amount' =>  $setPrice, 
                        'currency' => 'UAH', 
                        'product' =>  $product->id,
                    ]);
                   
                    $customer = $stripe->customers->create([
                        'name'  => $firstName,
                        'metadata' => [
                        'order_id' => $orderId,
                        'phone' => $userTel,
                        ],
                    ]);
                    
                    $checkout = $stripe->checkout->sessions->create([
                        'success_url' => 'http://localhost/project-shop/pages/all-products-page.php',
                        'cancel_url'  => 'https://localhost/project-shop/pages/cancel_url.php',
                        'customer'    => $customer->id,
                        'line_items'  => [
                            [
                            'price'    => $price->id,
                            'quantity' => 1, 
                            ],
                          ],
                        'mode' => 'payment',
                        'metadata' =>
                        [
                        'order_id' => $orderId,
                        ],
                    ]);
                    header('Location: ' . $checkout->url);
                    // header('Location: ../pages/all-products-page.php');
                }  catch (\Stripe\Exception\ApiErrorException $e) {
                    
                    echo "файл: " . $e->getFile()  . $e->getMessage() . 'Рядок' . $e->getLine() . 'Файл:' . $e->getFile();
                    error_log("файл: " . $e->getFile() . " Рядок: " . $e->getLine()  . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/checkout.log");
                  
                } catch (\Exception $e) {
                    
                    error_log("файл: " . $e->getFile() . " Рядок: " . $e->getLine()  . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/checkout.log");
                    echo "файл: " . $e->getFile() . $e->getMessage() . 'Рядок' . $e->getLine() . 'Файл:' . $e->getFile();
                }
        

                foreach ($data as $values ) {
                    $productId       = $values['product_id'];
                    $productPrice    = $values['product_price'];
                    $productQuontity = $values['product_quontity'];   
                    
                    $sqlInsertItems    = "INSERT INTO order_items (order_id, product_id, quantity, item_price) VALUES ('$orderId','$productId', '$productQuontity', '$productPrice')";
                    $resultInsertItems = mysqli_query($connect, $sqlInsertItems);

                    if( $resultInsertItems ) {


                        $sqlDeleteQuota = "DELETE  FROM quota WHERE product_id = $productId AND user_id = $user_id";
                        $resultDeleteQuota = mysqli_query($connect, $sqlDeleteQuota);

                        if(!$result)  {
                            throw new Exception('помилка видалення даних продукту з таблиці quota' . mysqli_error($connect));
                        }

                    } else { 
                        throw new Exception('помилка при додавання даних в order_items' . mysqli_error($connect));
                    }
                } 

            } else {
            throw new Exception('помилка в запиті додавання даних в orders' . mysqli_error($connect));
        }
    
    } else {
        header('Location: ../pages/design-products-page.php');
        invalidMessage('Ви заповнили не всі дані для оформлення зямовлення, спробуйте ще раз');
        throw new Exception('помилка при валідації форми замовлення продуктів');
    }

} 
 catch(Exception $e) {
    error_log("файл: " . $e->getFile() . " Рядок: " . $e->getLine()  . "  Повідомлення: " . $e->getMessage() . PHP_EOL, 3, "../var/log/design-products.log");
}
 

mysqli_close($connect);
