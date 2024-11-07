<?php
    spl_autoload_register(function ($class){
        require './classes/'.$class.'.php';
    });
    try{
        $mgr   = new ProductManager();
        $title = htmlspecialchars($mgr->pageTitle(), ENT_QUOTES, 'UTF-8');
    }catch(Exception $e){
        die('Error initializing Product Manager:' . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./assets/css/style.css"/>
            <title><?= $title ?></title>
        </head>
    <body>
        <h1><?= $title ?></h1>   
        <hr>
        <div class="container">
    
