<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $connection = null;
    try {
        $connection = new PDO("mysql:host=$servername;dbname=brigiquiz",$username);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die('<h1 style="margin:0;font-family:sans-serif;">Nem sikerült csatlakozni az adatbázishoz</h1><p style="margin:0;font-family:sans-serif;">' . utf8_encode($e->getMessage()) . '</p>');
    }

    if(!isset($_SESSION['valaszolt-kerdesek'])){
        $_SESSION['valaszolt-kerdesek'] = array();
    }

    if(isset($_SESSION['username'])){
        if(!isset($_SESSION['pontok'])){
            $_SESSION['pontok'] = 0;
        }
    }

    function kerdesKesz($kerd){
        array_push($_SESSION['valaszolt-kerdesek'],$kerd);
    }
?>