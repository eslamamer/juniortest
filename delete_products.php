<?php
    require_once 'classes/ProductManager.php';
    header("Content-Type: application/JSON");
    
    $data = json_decode(file_get_contents('php://input'), true);
    try{
        $mgr = new ProductManager();
        if(!empty($data['ids'])){
            foreach($data['ids'] as $id){
                $mgr->deleteProduct($id);
            }
        }   
    }catch(Exception $e){
        throw new Exception("Data Error:" . $e->getMessage());
    }
?>