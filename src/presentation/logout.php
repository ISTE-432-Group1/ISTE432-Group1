<?php
    session_name("iste432_project");
    session_start();
    session_unset();
    session_destroy();
    header('Location: ./login.php');
    exit;
?>