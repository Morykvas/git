<?php 
/**
 * сторінка оформленння замовлення
 * користувач переходить на цю сторінку для введення всіх необхідних даних для покупки товару
 */
 session_start();
 if(!isset($_SESSION)) {echo 'сесія не працює';} 
 define('CSS_DIR', '../');
 include_once '../header.php';
 include_once '../connect.php';

 ?>

<section class="design-product-page">
    <div class="container">
        <div class="item-title-design">
                <a href="basket-page.php">повернутись</a>
        </div>
        <div class="wrapper-design-products">
            <div class="wrapper-content">
                <?php 
                    $curentUserId = $_SESSION['profile']['user_id'];
                    $sql = "SELECT products.user_id, products.product_image, products.product_id, products.product_name, products.product_price, products.product_description, products.product_quontity, SUM(quota.product_quontity) AS total_quontity FROM products INNER JOIN quota ON products.product_id = quota.product_id WHERE quota.user_id = $curentUserId GROUP BY products.user_id, products.product_image,  products.product_id, products.product_id, products.product_name, products.product_price, products.product_description, products.product_quontity";
                    $result = mysqli_query($connect, $sql);
                ?>
                <div class="item-products-design">
                    <?php 
                        $dataArray = [];
                        while($row = mysqli_fetch_assoc($result)) :  
                        $productIds[] = [
                         'product_id'       =>  $row['product_id'],
                         'product_price'    =>  $row['product_price'],
                         'product_quontity' =>  $row['total_quontity'],
                        ];     
                      ?>
                        <div class="products_card">
                            <div class="wrapp-image-card">
                                <img src="data:image/jpg;base64, <?php echo  base64_encode($row['product_image']);?>" />
                            </div>
                            <div class="wrapp-desc">
                                <div class="item-desc">
                                    <h4><?= $row['product_name']; ?></h4>
                                </div>
                                <div class="item-desc">
                                    <p><?= $row['product_description']; ?></p>
                                </div>
                                <div class="item-desc">
                                    <span class="tittle-quontity">Кількість:</span><p class="num-quontity"><?= $row['total_quontity']; ?><span class="tittle-products">шт<span></p>
                                </div>
                                <div class="item-desc">
                                    <p class="price-card"><?= $row['product_price']; ?></p><span>грн</span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php  
                 $ukrainianCities = [
                    "Вінницька" => "Хмільник",
                    "Волинська" => "Луцьк",
                    "Дніпропетровська" => "Кривий Ріг",
                    "Донецька" => "Маріуполь",
                    "Житомирська" => "Коростень",
                    "Закарпатська" => "Ужгород",
                    "Запорізька" => "Мелітополь",
                    "Івано-Франківська" => "Коломия",
                    "Крим" => "Ялта",
                    "Київська" => "Бровари",
                    "Кіровоградська" => "Олександрія",
                    "Луганська" => "Сєвєродонецьк",
                    "Львівська" => "Дрогобич",
                    "Миколаївська" => "Вознесенськ",
                    "Одеська" => "Южне",
                    "Полтавська" => "Миргород",
                    "Рівненська" => "Острог",
                    "Сумська" => "Конотоп",
                    "Тернопільська" => "Бережани",
                    "Харківська" => "Ізюм",
                    "Херсонська" => "Скадовськ",
                    "Хмельницька" => "Хмільник",
                    "Черкаська" => "Умань",
                    "Чернівецька" => "Вижниця",
                    "Чернігівська" => "Ніжин"
                 ];
                $novaposhtaOffices = [
                    ["street" => "Соборна", "number" => "10"],
                    ["street" => "Вокзальна", "number" => "5"],
                    ["street" => "Лісна", "number" => "17"],
                    ["street" => "Свободи", "number" => "12"],
                    ["street" => "Центральна", "number" => "8"],
                    ["street" => "Зелена", "number" => "23"],
                    ["street" => "Героїв Праці", "number" => "14"],
                    ["street" => "Паркова", "number" => "3"],
                    ["street" => "Київська", "number" => "22"],
                    ["street" => "Лесі Українки", "number" => "7"],
                    ["street" => "Шевченка", "number" => "15"],
                    ["street" => "Гагаріна", "number" => "11"],
                    ["street" => "Привокзальна", "number" => "4"],
                    ["street" => "Заводська", "number" => "19"],
                    ["street" => "Садова", "number" => "27"],
                ];       
                ?>
                <div class="item-form-design">
                    <form id="payment-form" class="form-checkout" action="../data_processor/design-products.php" method="post">
                        <h2>Оформлення покупки</h2>
                        <input type="hidden" name="json_data"  value="<?php echo htmlspecialchars(json_encode($productIds)); ?>">
                        <div class="wrapp-user-data">
                            <div >
                                <input id="name" type="text"   name="last_name"  placeholder="Прізвище">
                                <input type="text"   name="first_name" placeholder="Імя">
                            </div>
                            <div>
                                <input type="text"   name="surname"    placeholder="По-батькові">
                                <input  id="phone" type="tel"    name="user_tel"   placeholder="Тел:"> 
                            </div>
                        </div>
                        
                        <h3 class="title-form">Cпособи доставки</h3>

                        <div class="vrapper-radio">
                            <div class="item-radio-mail">    
                                <input type="radio" name="is_order" value="one">
                                <span class="switch-span" >Відділення - Нова пошта</span>       
                            </div>
                            <div class="item-radio-mail">
                                <input type="radio" name="is_order" value="two"> 
                                <span class="switch-span" >Адресна доставка - Нова пошта</span>  
                            </div>
                        </div>
                        <select class="select-city" name="select-region">
                           <option>Оберіть область</option> 
                           <?php 
                                foreach ($ukrainianCities as $region => $city) {
                                    echo '<option value="'.$region.'">'.  $region .'</option>';
                                }
                            ?>
                        </select>
                        <select class="select-city"  name="select-sity" >
                           <option>Оберіть місто</option> 
                           <?php 
                                foreach ($ukrainianCities as $region => $city) {
                                    echo '<option value="'.$city.'">'.  $city .'</option>';
                                }
                            ?>
                        </select>
                        <select class="select-city"  name="select-place-delivery">
                           <option class="option">Оберіть відділення</option> 
                           <?php 
                                foreach ($novaposhtaOffices as $office) {
                                    echo '<option >'.'Відділення № '.$office['number'].'   вул.'.$office['street'].'</option>';
                                }
                            ?>
                        </select>
                       
                        <div class="wrapper-card-data">
                              
                            <h3 class="title-form">Оплата</h3>
                            <?php
                                 if(isset( $_SESSION['client_secret'] )) {
                                    $clientSecret = $_SESSION['client_secret'];
                                }
                                ?>
                            
                            <input type="hidden" id="client-secret" value="<?php echo $clientSecret; ?>">
                            <div id="card-element"></div>
                            <div class="wrapp-number-card">
                                <label for="ccnum">Номер картки</label>
                                <input  name="card-number" id="ccn" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="0000 0000 0000 0000">
                            </div>
                            <div class="label">
                                <span>Місяць дії </span>
                                <span>Рік дії </span>
                                <span>CVV </span>
                            </div> 
                            <div class="wrapp-cvv-exp-year">
                               <div class="input-mont">
                                    <input type="text" name="expmonth" placeholder="00/">

                                </div>
                                <div>
                                    <input type="text" name="expyear" placeholder="/00">
                                </div>
                                <div>
                                    <input type="text" name="cvv" placeholder="000">
                                </div>
                            </div>
                        </div>
                        <input type="submit"  id="submit-button" value="Продовжити">
                        <div id="error-message"></div>
                        <?php  
                            sessionMessageInvalid('invalidMessage');
                            sessionMessageValid('validationMessage');
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php 
define('SCRIPT_DIR', '../js/');
include_once '../footer.php';
mysqli_close($connect);
?>