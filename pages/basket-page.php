<?php
/**
 * сторінка на якій у нас виводять товари які вибрав користувач для покупки
 * в ньому він може редагувати кількість продукту і та оформлювати його вже для оплати на сторінці design-products-page.php
 */
session_start();
if (!isset($_SESSION)) {echo 'сесія непрацює';}
define('CSS_DIR', '../');
include_once '../connect.php';
include_once '../header.php';
?>
<section class="basket-page">
    <div class="container">
        <div class="wprap-basket-link">
            <a class="basket_link" href="all-products-page.php">повернутись</a>
            <a class="basket_link" href="design-products-page.php">оформлення замовлення</a>
        </div>
        <h1 class="tittle-basket">Кошик</h1>
        <div class="wrapper-basket">
            <?php if($_SESSION['profile']) : ?>
                <?php 
                    $curentUserId = $_SESSION['profile']['user_id'];
                    $sql = "SELECT products.product_image, products.product_id, products.product_name, products.product_price, products.product_description, products.product_quontity, SUM(quota.product_quontity) AS total_quontity FROM products INNER JOIN quota ON products.product_id = quota.product_id WHERE quota.user_id = $curentUserId GROUP BY products.product_image,  products.product_id, products.product_id, products.product_name, products.product_price, products.product_description, products.product_quontity";
                    $result = mysqli_query($connect, $sql);
                ?>
                <div class="my_products">
                    <?php  while($row = mysqli_fetch_assoc($result)) : ?>
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
                                    <span class="tittle-quontity">Кількість:</span><p class="num-quontity"><?= $row['product_quontity']; ?><span class="tittle-products">шт<span></p>
                                </div>
                                <div class="item-desc">
                                    <p class="price-card"><?= $row['product_price']; ?></p><span>грн</span>
                                </div>
                            </div>
                            <?php 
                                $product_id = $row['product_id'];
                                $product_quontity = $row['product_quontity'];
                            ?>
                            <div class="wrapp-delete-form">
                                <form class="form-card-sproducts" action="../data_processor/buy-products.php" method="post">
                                    <?php  $quontityTotal = $row['total_quontity'];?>  
                                    <input class="select-order" type="number" name="buy_quontity" min="" max="" value="<?= $quontityTotal;?>">
                                    <input type="hidden" name="product_id" value="<?= $product_id;?>">
                                    <input type="hidden" name="user_id" value="<?= $curentUserId; ?>">
                                    <input class="order-button" type="submit" value="Оформити">
                                </form>
                                <form class="form-delete-product" action="../data_processor/delete-products.php" method="post">
                                    <input type="hidden" name="user_id" value="<?= $curentUserId; ?>">
                                    <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                                    <input type="hidden" name="product_quontity" value="<?= $product_quontity; ?>">   
                                    <div class="image-container">
                                        <div class="overlay"></div>
                                        <input class="delete-button" type="submit" value="">
                                    </div>                                  
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
mysqli_close($connect);
define('SCRIPT_DIR', '../js/');
include '../footer.php';
?>