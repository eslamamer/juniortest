<?php
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
     
     $pageTitle = 'product add';
     include './includs/header.php';
?>
<form action="<?= htmlspecialchars('save_product.php')?>" method="POST" id="product_form">  
     <label for="sku">SKU</label>
     <input type="text" name="sku" id="sku" autocomplete ="off" required /> 

     <label for="name">Name</label>
     <input type="text" name="name" id="name" autocomplete ="name" required /> 

     <label for="price">Price ($)</label>
     <input type="number" step="0.01" name="price" id="price" required />
     
     <label for="productType">type switcher</label>
     <select name="type" id="productType" required>
          <option value="0">Select Type</option>
          <option value="1">Book</option>
          <option value="2">DVD</option>
          <option value="3">Furniture</option>
     </select>

     <div id="Book" class="types">
          <label for="weight">weight (KG)</label>
          <input type="number" step="0.01" name="weight" id="weight" /> 
          <p>please provide weight in KG</p>
     </div>

     <div id="DVD" class="types">
          <label for="size">size (MB)</label>
          <input type="number" step="0.01" name="size" id="size" />
          <p>please provide size in MB</p>
     </div>
          
     <div id="Furniture" class="types">
          <label for="height">height (CM)</label>
          <input type="number" step="0.01" name="height" id="height" />
          
          <label for="width">width (CM)</label>
          <input type="number" step="0.01" name="width" id="width" />
     
          <label for="length">length (CM)</label>
          <input type="number" step="0.01" name="length" id="length" />
          <p>please provide diminssion in CM</p>
     </div>  
     <div class="control-form">
          <input class="btn-primary" type="submit" value="Save" name="save">
          <a href="./" class="btn-danger">cancel</a> 
     </div>
     <div class="error-messages"></div>
</form>
<?php include './includs/footer.php';?>