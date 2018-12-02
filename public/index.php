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

// auth middleware
include_once('../modules/middleware.php');

// routing
include_once('../modules/route.php');

$conn->close();
