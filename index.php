<?php require_once './includs/header.php' ?>
    <div class='control-list'>
        <a class="btn-primary" href="./addproduct.php">ADD</a>
        <button id="delete-product-btn" class="btn-danger">MASS DELETE</button>
    </div>
    <?php $products = $mgr->fetchproducts();
           if(empty($products)){
                echo"<div style = 'margin : 5rem 1rem '>
                        <p>".htmlspecialchars('product list is empty')."</p>
                    </div>";
            }else{
                echo "<div class='items'>";
                forEach($products as $product){ ?>
                <div class="item">
                    <input
                        type="checkbox"
                        name = "del"
                        class="delete-checkbox" 
                        data-id="<?= htmlspecialchars($product[0]) ?>"
                    />
                    <div class="item-data">
                        <?php forEach($product as $k => $attr){ $k++ ?>
                            <p><?= htmlspecialchars($product[$k]) ?></p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            </div>
<?php } require_once './includs/footer.php' ?>