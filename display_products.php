<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
    require_once "classes/ProductManager.php";
    $mgr = new ProductManager();
    try{
        $displayPro = $mgr->fetchproducts();
        if(!empty($displayPro)) 
        echo json_encode(['display' => $displayPro]);
    }catch(Exception $e){
        echo json_encode(['error' => 'unexpected error: ' .$e->getMessage()]);
    }
?>