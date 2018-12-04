<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//var_dump(phpinfo());die;
$time = time();

session_start();
require_once('../libs/func.php');

$errorMessage = '';

$env = getEnvs('../.env');
$conn = new mysqli($env['servername'], $env['username'], $env['password'], $env['dbname']);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = getUserId();


if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'migration') {
    include_once ('../source/migration.php');
}

$sql = 'select count(*) from users';
$checkSql = $conn->query($sql);
if($checkSql) {
    $usersCount = $checkSql->fetch_assoc();
}
if ($conn->error) {
    print_r($conn->error);
    echo '<br>No data in database. Do you want to <a href="/?do=migration">run migration</a>?';
    die;
}


// auth middleware
include_once('../modules/middleware.php');

// routing
include_once('../modules/route.php');

$conn->close();
