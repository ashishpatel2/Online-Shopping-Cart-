<?php
    session_start();
    $dsn = "mysql:host=courses;dbname=z1892226";
    $pdo = new PDO($dsn, 'z1892226', '2002Feb20');

    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // Page is set to home (home.php) by default, so when the visitor visits that will be the page they see.
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'products';
    // Include and show the requested page
    include $page . '.php';
?>