<?php
/************************************************************************
 *
 *  Домашнее задание
 *  1. Вывести таблицу с полями
 *  id, user_link, comment, category,total_spent, created_at,
 *  где номер категории заменить на название категории,
 *  updated_at привести к формату год-месяц-день
 *  и total_spent вывести со знаком валюты.
 *
 *  2. добавить фильтр по категориям
 *
 *  3. редактирование категорий
 *
 *  4. поиск по комментариям
 *
 *  5. Created new branch...
 *
 ************************************************************************/

if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'migration') {
    include_once ('../source/migration.php');
}

$descParam = '-'; // начальное значение параметра типа сортировки для формирования ссылки
$arrow = '&uarr;'; // начальное значение типа стрелки

$sql = 'select value from app_settings where name = "categories"';
$categoriesData = $conn->query($sql);
$categoriesValue = $categoriesData->fetch_assoc();
if ($conn->error) {
    print_r($conn->error);
    die;
}
$categories = json_decode($categoriesValue['value'], true);

if(!$categories){
    echo 'No data in database. Do you want to <a href="/?do=migration">run migration</a>?';
    die;
}
$descStatus = false; // начальное значение для переменной хранящей состояние обратной сортировки для проверки условий

# Сохраняю в куки состояние фильтра и сортировки
if (isset($_REQUEST['search'])) setcookie("search", $_REQUEST['search']);
if (!isset($_REQUEST['search'])
    && !empty($_COOKIE['search']))
    $_REQUEST['search'] = $_COOKIE['search'];

if (isset($_REQUEST['sort'])) setcookie("sort", $_REQUEST['sort']);
if (!isset($_REQUEST['sort'])
    && !empty($_COOKIE['sort']))
    $_REQUEST['sort'] = $_COOKIE['sort'];

if (isset($_REQUEST['sort']) && $_REQUEST['sort'][0] == '-') { // проверка параметра пришедшего из ссылки. если условие выполняется меняем значения по умолчанию
    $descStatus = true;
    $descParam = '';
    $arrow = '&darr;';
}


$sql = 'select * from user_request';
$userRequestData = $conn->query($sql);
$userRequest = $userRequestData->fetch_all(MYSQLI_ASSOC);
if ($conn->error) {
    print_r($conn->error);
    die;
}

foreach ($userRequest as $key => $row) {
    $userRequest[$key]['categoryName'] = $categories[$row['category']]; // замена номеров категорий на их имена
}

if (isset($_REQUEST['item'], $_REQUEST['update_cat'])) {
    $item = array_filter($userRequest, function ($innerArray) {
        return in_array($_REQUEST['item'], $innerArray);
    });
    if (array_shift($item)['category'] !== $_REQUEST['update_cat']) {
        changeCategory($conn, $_REQUEST['item'], $_REQUEST['update_cat']);
    }
}

$userRequest = sorter($userRequest, $descStatus);
if (isset($_REQUEST['sort'])) {
    $sortOrder = 'arrow' . str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', '', $_REQUEST['sort'])))); // формирование имени переменной хранящей текущую стрелку
    $$sortOrder = $arrow; // присвоение текущей стрелки
}

$user = getUserData($conn, $userId);
require_once('../view/template.php');