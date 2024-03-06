<?php 
/**
 * сторінка продуктів юзера 
 * на цю сторінку може потрапити тільки авторизований користувач і там тільки продукти які він додав для себе 
 */
    session_start();  
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    define('CSS_DIR', '../');
    include_once "../connect.php";
    include_once '../header.php';
   
 ?>
<section class="private-products-page">
    <?php 
        $user_id = $_SESSION['profile']['user_id'];
        $sql = "SELECT * FROM products WHERE user_id=$user_id AND is_order=0";
        $result = mysqli_query($connect, $sql);
    ?>
    <div class="container">
        <div class="item_button_back">
            <a href="../customer/index.php">повернутись</a>
        </div>
        <div class="wrapper-products">
            <div class="wrapper-title">
                <h1>Мої продукти</h1>
            </div>
            <div class="my_products">
                <?php while($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="products_card">
                        <div class="wrapp-image-card">
                            <img src="data:image/jpeg;base64, <?= base64_encode($row['product_image']);?>" />
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
                            $_SESSION['edit-product_id'] = $row['product_id'];
                            $product_id =  $_SESSION['edit-product_id'];
                        ?>
                        <a class="edit_link" href="edit-product-page.php?product_id=<?= $product_id; ?>">редагувати</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>

<?php 
include_once '../footer.php'; 
mysqli_close($connect);
?>