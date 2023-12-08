<?php

define('DB_dsn', 'mysql:dbname=student_management_system;host=localhost');
define('DB_user', 'root');
define('DB_password', '');
define('ADMIN', 'admin');
define('ADMIN_PASSWORD', 'secret1');



session_start();
$db;


$g = filter_var_array($_GET, FILTER_SANITIZE_STRING);
$p = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$msg = "";



try {


    $db = new PDO(DB_dsn, DB_user, DB_password);
} catch (PDOException $err) {


    die("Could not connect to database");
}
