<?php

$descParam = '-'; // начальное значение параметра типа сортировки для формирования ссылки
$arrow = '&uarr;'; // начальное значение типа стрелки
$descStatus = false; // начальное значение для переменной хранящей состояние обратной сортировки для проверки условий

$categoriesData = $conn->query('select * from categories'); // исполнение запроса
$categoriesArray = $categoriesData->fetch_all(); // представление результата в виде массива
if ($conn->error) { // обработка ошибок запроса
    print_r($conn->error);
    die;
}

$categories = [];
while ($row = array_shift($categoriesArray)) {
    $categories[array_shift($row)] = array_shift($row);
}

# Сохраняю в куки значение фильтра
if (isset($_REQUEST['search'])) setcookie("search", $_REQUEST['search']);
if (!isset($_REQUEST['search'])
    && !empty($_COOKIE['search']))
    $_REQUEST['search'] = $_COOKIE['search']; // если нет фильтра в запросе, но есть в куках - использую для фильтра значение из кукис

# Сохраняю в куки значение сортировки
if (isset($_REQUEST['sort'])) setcookie("sort", $_REQUEST['sort']);
if (!isset($_REQUEST['sort'])
    && !empty($_COOKIE['sort']))
    $_REQUEST['sort'] = $_COOKIE['sort']; // если нет сортировки в запросе, но есть в куках - использую для сортировки значение из кукис

if (isset($_REQUEST['sort']) && $_REQUEST['sort'][0] == '-') { // проверка параметра пришедшего в запросе. если условие выполняется меняем значения по умолчанию
    $descStatus = true;
    $descParam = '';
    $arrow = '&darr;';
}

$sql = 'select * from user_request'; // получаю все данные из таблицы user_request
$userRequestData = $conn->query($sql); // выполняю запрос
$userRequest = $userRequestData->fetch_all(MYSQLI_ASSOC); // предствляю результат в виде ассоциативного массива
if ($conn->error) { // обрабатываю ошибки
    print_r($conn->error);
    die;
}

foreach ($userRequest as $key => $row) {
    $userRequest[$key]['categoryName'] = $categories[$row['category']]; // добавление ключа categoryName с именем категории
}

if (isset($_REQUEST['item'], $_REQUEST['update_cat'])) { // проверка что пришла команда на изменение категории
    $item = array_filter($userRequest, function ($innerArray) { // поиск в массиве строк таблицы нужной строки
        return in_array($_REQUEST['item'], $innerArray);
    });
    if (array_shift($item)['category'] !== $_REQUEST['update_cat']) { // проверка на то что в строке записана не та категория, которая пришла в запросе
        changeCategory($conn, $_REQUEST['item'], $_REQUEST['update_cat']); // функция записи новой категории
    }
}

$userRequest = sorter($userRequest, $descStatus); // подключение функции сортировки
if (isset($_REQUEST['sort'])) { // проверка на параметр сортировки в запросе
    $sortOrder = 'arrow' . str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', '', $_REQUEST['sort'])))); // формирование имени переменной хранящей текущую стрелку
    $$sortOrder = $arrow; // присвоение текущей стрелки
}

require_once('../view/template.php'); // подключаю представление