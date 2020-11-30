<?php
    require_once './libs/egyeb.php';
    if(isset($_SESSION['kerdes']) && isset($_SESSION['megoldas']) && isset($_GET['valasz']) && !isset($_SESSION['vege'])){
        $kerdes = $_SESSION['kerdes'];
        $valasz = $_GET['valasz'];
        $megoldas = $_SESSION['megoldas'];
        if($megoldas == $valasz || $megoldas == '__minden__'){
            $_SESSION['pontok']++;
        }
        kerdesKesz($kerdes);
    }
    header('location:index.php');
?>