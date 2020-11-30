<?php
    require_once './libs/egyeb.php';
    session_unset();
    session_destroy();
    header('location:index.php');
?>