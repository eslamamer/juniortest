<?php
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
// Set the header to return JSON response

require_once './classes/ProductManager.php';
$mgr = new ProductManager();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
     try{
          $input  = json_decode(file_get_contents('php://input'), true);
          $data   = [];
          $errors = [];
          if(isset($input['sku']) && trim($input['sku']) !== ""){
               $isSKUExist = $mgr->isSkuExist($input['sku']);
               if(!$isSKUExist){
                    $data['sku'] = htmlspecialchars(filter_var($input['sku'], FILTER_SANITIZE_SPECIAL_CHARS));
               }else{
                    $errors [] = 'SKU already Exist';
               }
          }else{
               $errors [] = 'SKU cannot be empty';
          }

          if(isset($input['name']) && trim($input['name']) !== ""){
               $data['name'] = htmlspecialchars(filter_var($input['name'], FILTER_SANITIZE_SPECIAL_CHARS));
          }else{
               $errors [] = 'name cannot be empty';
          }

          if(isset($input['price']) && trim($input['price']) !== ""){
               if(is_numeric($input['price']) && $input['price'] >= 0){
                    $data['price'] = htmlspecialchars(filter_var($input['price'], FILTER_SANITIZE_SPECIAL_CHARS));
               }else{
                    $errors [] = 'price must be apositive number';
               }
          }else{
               $errors [] = 'price cannot be empty';
          }

          if(isset($input['type']) && $input['type'] > 0){
               $data['type'] = htmlspecialchars(filter_var($input['type'], FILTER_SANITIZE_SPECIAL_CHARS));
          }else{
               $errors [] = 'select product type'; 
          }

          $visibleInputs = json_decode($input['visibleInputs'], true);
          if(isset($visibleInputs)){
              foreach($visibleInputs as $visibleInput){
               $data [] = $visibleInput;
               $mgr->validateAttr($visibleInput, $input, $data, $errors);
              }
          }
          
          if (empty($errors)) {
               $mgr->save($data);
               if($mgr->isSkuExist($data['sku']))
               echo json_encode(['success' => true , 'data'  => $data]);
               exit();
          } else{
               echo json_encode(['success' => false, 'errors'=> $errors]);
               exit();
          }
     }catch (Exception $e) {
          error_log("unexpected erro: " . $e->getMessage());
     }
}
?>
