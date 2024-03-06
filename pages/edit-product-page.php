<?php 
/**
 * сторінка для редагування продукту 
 * тут знаходиться форма в якій можна вказати будь які зміни в описі для редагування 
 */
    session_start();  
    if(!isset($_SESSION)) { echo 'сесія непрацює';}
    define('CSS_DIR', '../');
    include_once "../connect.php";
    include_once '../header.php';
?>
<section class="private-products-page">
    <div class="container">
    <div class="wrapper-title-edit">
        <a href="private-products-page.php">повернуись</a>
    </div>
    <div class="update-products"> 
        <?php 
            $product_id = $_GET['product_id'];  
            $userId = $_SESSION['profile']['user_id'];
        ?>
        <form class="form_product" action="../data_processor/edit-products.php"  method="post"  enctype="multipart/form-data">
                <h2>Редагування продукту</h2>
                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                <input type="hidden" name="user_id" value="<?= $userId ?>">

                <input type="text" name="product_name" placeholder="Назва продукту">

                <input type="text" name="product_price" placeholder="Ціна продукту">

                <textarea class="product_area" name="product_description" placeholder="Опис продукту" ></textarea>

                <input type="text" name="product_quontity" placeholder="Наявна кількість">

                <label for="file">Оберіть фото:</label>
                <input type="file" name="file" accept="image/*">

                <input type="submit" value="Редагувати">
                
                <?php 
                   echo sessionMessageInvalid('invalidMessage');
                   echo sessionMessageValid('validationMessage');
                ?>
        </form>
   </div> 
    </div>
</section>

<?php 
include_once '../footer.php'; 
mysqli_close($connect);
?>