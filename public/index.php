<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//var_dump(phpinfo());die;
$time = time();

session_start();
require_once('../libs/func.php');

$errorMessage = '';

$servername = "localhost";
$username = "chancellor";
$password = "1";
$dbname = 'chandb';
$env = getEnvs('../.env');

$conn = new mysqli($env['servername'], $env['username'], $env['password'], $env['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# action process
if (!empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'auth') {
        include_once('../modules/auth.inc.php');
    } elseif ($_REQUEST['action'] == 'sign_up') {
        include_once('../modules/sign_up.inc.php');
    }
}

// routing
if (empty($_SERVER['REDIRECT_URL']) || $_SERVER['REDIRECT_URL'] == '/') {
    if (empty($_SESSION['session_hash'])
        || empty($_COOKIE['session_hash'])
        || $_SESSION['session_hash'] != $_COOKIE['session_hash']) {
        include_once('../modules/auth.inc.php');
    } else {
        include_once('../modules/json_table.inc.php');
    }
} elseif ($_SERVER['REDIRECT_URL'] == '/sign_up') {
    include_once('../modules/sign_up.inc.php');
} elseif ($_SERVER['REDIRECT_URL'] == '/logout') {
    setcookie('session_hash', '', 0);
    header('Location: /');
}

$conn->close();