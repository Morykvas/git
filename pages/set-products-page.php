<?php 
    /**
     * сторінка на якій ми вносимо товар, вказуємо параметри товару та та місце де він буде відображатись
     * на головній сторінці всіх продукті або ж на закритій сторінці користувача
     */
    session_start();  
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    include_once "../connect.php";
    define('CSS_DIR', '../');
    include_once '../header.php';
   
 ?>
<section class="products-page">
    <div class="container">
        <div class="wrapp-tytle-link">
            <a href="../customer/index.php">повернуись</a>
        </div>
        <div class="wrapper-product-content">
            <?php if(isset($_SESSION['profile'])) : ?>
                <form class="set_form_product" action="../data_processor/set-process_product.php"  method="post"  enctype="multipart/form-data">
                    <h2 class="title">Завантажте продукт</h2>
                        <input type="hidden" name="user_id" value="<?= $_SESSION['profile']['user_id']; ?>">
                        
                        <input type="text" name="product_name" placeholder="Назва продукту">

                       
                        <input type="text" name="product_price" placeholder="Ціна продукту">

                       
                        <textarea class="product_area" name="product_description" placeholder="Опис продукту"></textarea>
 
                        
                        <input type="text" name="product_quontity" placeholder="Наявна кількість">

                        <label for="fileInput">Оберіть фото</label>
                        <input value="Оберіть фото" type="file" name="file" accept="image/*" >
      
                        <div class="item-radio">

                            <input type="radio" name="is_order" value="one">
                            <span class="switch-span" >приватний</span> 
                        </div>

                        <div class="item-radio">
                            <input type="radio" name="is_order" value="two"> 
                            <span class="switch-span" >публічний</span>  
                        </div>
                        <?php  
                            $selectCategories = 'SELECT category_name FROM categories';
                            $resultCategories = mysqli_query($connect, $selectCategories);
                        ?> 
                        <select name="categiries" class="categiry_select">
                            <option value="">Категорія</option>
                            <?php  while($row =  mysqli_fetch_assoc( $resultCategories )){
                                    $categories = $row['category_name'];
                                    echo '<option value=' . $categories . '>' . $categories . '</option>';
                                }
                            ?>
                        </select>

                        <input type="submit" value="Викласти">
            <?php endif; ?>
                <?php
                    echo sessionMessageInvalid('invalidMessage');
                    echo sessionMessageValid('validationMessage');
                ?>
                </form>
            <?php mysqli_close($connect); ?>
        </div>
    </div>
</section>  
<?php include_once '../footer.php'; ?>