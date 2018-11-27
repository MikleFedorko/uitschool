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
 *  5. Created new branch
 *
 ************************************************************************/

require_once('../libs/func.php');

$filename = '../source/testJsonDataToSort.txt'; // закрепляет именованый ресурс, указанный в аргументе filename, за потоком.
$descParam = '-'; // начальное значение параметра типа сортировки для формирования ссылки
$arrow = '&uarr;'; // начальное значение типа стрелки

# Json со списом категорий
$categoryJson = '{"4":"Assets","7":"Christmas","2":"Clothes","3":"Easter","5":"Gameplay","8":"Halloween","6":"Release theme","1":"Scenery","10":"St. Patrick\'s","9":"St.Valentine","11":"Stylist"}';
$categories = json_decode($categoryJson, true);// преобразование json строки в массив
$descStatus = false; // начальное значение для переменной хранящей состояние обратной сортировки для проверки условий

# Сохраняю в куки состояние фильтра и сортировки
if(isset($_REQUEST['search'])) setcookie("search", $_REQUEST['search']);
if(!isset($_REQUEST['search'])
    && !empty($_COOKIE['search']))
    $_REQUEST['search'] = $_COOKIE['search'];

if(isset($_REQUEST['sort'])) setcookie("sort", $_REQUEST['sort']);
if(!isset($_REQUEST['sort'])
    && !empty($_COOKIE['sort']))
    $_REQUEST['sort'] = $_COOKIE['sort'];

if(isset($_REQUEST['sort']) && $_REQUEST['sort'][0] == '-') { // проверка параметра пришедшего из ссылки. если условие выполняется меняем значения по умолчанию
    $descStatus = true;
    $descParam = '';
    $arrow = '&darr;';
}

$mytext = freader($filename);
$arr = json_decode($mytext, true); // преобразование json строки в массив

foreach($arr as $key => $row) {
    $arr[$key]['categoryName'] = $categories[$row['category']]; // замена номеров категорий на их имена
}

if(isset($_REQUEST['item']) && isset($_REQUEST['update_cat'])) {
    if($arr[$_REQUEST['item']]['category'] != $_REQUEST['update_cat']){
        $arr[$_REQUEST['item']]['category'] = $_REQUEST['update_cat'];
        fwriter($filename, $arr);
    }
}

$arr = sorter($arr, $descStatus);
if(isset($_REQUEST['sort'])) {
    $sortOrder = 'arrow' . str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', '', $_REQUEST['sort'])))); // формирование имени переменной хранящей текущую стрелку
    $$sortOrder = $arrow; // присвоение текущей стрелки
}

include('../view/template.php');