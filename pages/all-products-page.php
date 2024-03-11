<?php
/**
 * сторінка де виводяться всі продукти додані користувачем як публічні
 * продукти мжна сортувати за категоріями зазначеними в сайдбарі 
 * після вибору категоріх користувач потрапляє на сторінку категорї products-category-page.php
 */
    session_start();
    const CSS_DIR = '../';
    include_once '../header.php';
    include_once '../connect.php';
?>
<section class="all-products-page">
    <div class="container">
        <div class="item-all-products">
                <a href="../customer/index.php">повернутись</a>
                <a href="basket-page.php">кошик</a>
        </div>
        <h1 class="all-prod-tittle">Сторінка безкоштовних продуктів для Юрка</h1>
        <p>Юрко вибирай що хочеш, для тебе в мому магазині все безкоштовно</p>
        <div class="wrapper-all-products">
            <?php 
                $sql = "SELECT * FROM products WHERE is_order = 1 ORDER BY product_id DESC";
                $result = mysqli_query($connect, $sql);
            ?>
            
            <div class="wrapper_content_products">
                <div class="sidebar_categories_select">
                    <form class="form-select_category" action="../data_processor/select_category.php" method="post">
                        <h2>Категорії</h2>
                        <?php 
                            $sqlSelectCategories = "SELECT categories.category_id, categories.category_name, COUNT(products.product_id) as product_count FROM categories LEFT JOIN products ON categories.category_id = products.category_id AND  is_order = 1 GROUP BY categories.category_id, categories.category_name";
                            $sqlResultCategories = mysqli_query($connect, $sqlSelectCategories);
                            while($rowCategories = mysqli_fetch_assoc( $sqlResultCategories)) :
                                $quontityCategoryProd = $rowCategories['product_count'];
                                $categotyId   = $rowCategories['category_id'];
                                $categoryName = $rowCategories['category_name'];
                        ?>
                            <div class="wrapper_filter_category">
                            <div class="item-radio">
                                <input type="radio" name="order_category" value="<?= $categotyId ?>">
                                <span class="switch-span"><?= $categoryName ?></span>
                            </div>
                                <span class="num_category_prod">К-ть: <?= $quontityCategoryProd ?></span>
                            </div>
                            
                            <?php endwhile; ?>
                        <input class="category_button_select" type="submit" value="виконати">
                    </form>
                </div>
                <div class="my_products">
                    <?php while($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="products_card">
                             <?php  # var_dump($row['product_image']); ?> 
                        
                            <div class="wrapp-image-card">
                               
                                <img src="data:image/jpg;base64, <?php echo base64_encode($row['product_image']);?>" /> 
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
                            <div class="wrapper-form-cards">
                                <form class="form-card-sproducts" action="../data_processor/set_basket.php" method="post">
                                    <input type="hidden"  name="user_id" value="<?= $_SESSION['profile']['user_id'];?>">  
                                    <input type="hidden" name="product_id" value="<?= $row['product_id']; ?>">
                                    <select class="select-product_quontity" name="product_quontity"> 
                                        <?php 
                                        $productQuantity = $row['product_quontity'];
                                        for ($i = 1; $i <= $productQuantity; $i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <input type="submit" value="додати">
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
   </div>
</section>
<?php
    mysqli_close($connect);
    include_once '../footer.php';
?>