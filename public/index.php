<?php
/************************************************************************
 *
 *  Домашнее задание
 *
 ************************************************************************/

ini_set('display_errors', 1); // режим отоборажения ошибок
session_start(); // стартую сессию http://php.net/manual/ru/function.session-start.php

require_once('../libs/func.php'); // включение библиотеки функций

$errorMessage = ''; // переменная для хранения ошибок
$userId = getUserId(); // расшифровка из кукис айдишника пользователя
$conn = getDatabaseConnect(); // инициация соендинения с базой данных
if($userId) $user = getUserData($conn, $userId); // получаю все данные пользователя из базы

$path = @$_REQUEST['path'];
if(empty($userId)) $path = 'auth';
elseif(empty($path) && $userId) $path = 'json_table';

include_once("../modules/$path.inc.php");

$conn->close(); // закрытие соендинения с базой данных
