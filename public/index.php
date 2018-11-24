<?php
/************************************************************************
 *
 *  Домашнее задание от 15.11.18
 *  Вывести таблицу с полями
 *  id, user_link, comment, category,total_spent, created_at,
 *  где номер категории заменить на название категории,
 *  updated_at привести к формату год-месяц-день
 *  и total_spent вывести со знаком валюты
 *
 *
 ************************************************************************/
$descParam = '-'; // начальное значение параметра типа сортировки для формирования ссылки
$arrow = '&uarr;'; // начальное значение типа стрелки
# Json со списом категорий
$categoryJson = '{"4":"Assets","7":"Christmas","2":"Clothes","3":"Easter","5":"Gameplay","8":"Halloween","6":"Release theme","1":"Scenery","10":"St. Patrick\'s","9":"St.Valentine","11":"Stylist"}';
$category = json_decode($categoryJson, true);// преобразование json строки в массив
$descStatus = false; // начальное значение для переменной хранящей состояние обратной сортировки для проверки условий

if($_REQUEST['sort'][0] == '-') { // проверка параметра пришедшего из ссылки. если условие выполняется меняем значения по умолчанию
    $descStatus = true;
    $descParam = '';
    $arrow = '&darr;';
}

$filename = 'testJsonDataToSort.txt'; // закрепляет именованый ресурс, указанный в аргументе filename, за потоком.
if (!$fp = fopen($filename, 'r')) {
    echo "Не могу открыть файл ($filename)";
    exit;
}

$mytext = ''; // пустая переменная для записи данных из файла
while (!feof($fp)) { // проверка что указатель файла не достиг End Of File (EOF)
    $mytext .= fgets($fp); // функция берет чанк символо узаканной длинный из файла
}
fclose($fp); // закрывает поток
$arr = json_decode($mytext, true); // преобразование json строки в массив
foreach($arr as $key => $row) {
    $arr[$key]['categoryName'] = $category[$row['category']]; // замена номеров категорий на их имена
}

if (isset($_REQUEST['item']) && isset($_REQUEST['update_cat'])) {
    $arr[$_REQUEST['item']]['category'] = $_REQUEST['update_cat'];
//    var_dump(json_encode($arr));die;
    if (!$handle = fopen($filename, 'w')) {
        echo "Не могу открыть файл ($filename)";
        exit;
    }
    if (fwrite($handle, json_encode($arr)) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }
    fclose($handle);
}

$categorySelector = '<form method="get"><select name="search" class="filter">';
foreach($category as $key => $cat) {
    $condition = $_REQUEST['search'] == $key ? 'selected' : '';
    $categorySelector .= '<option value="' . $key . '" ' . $condition . '> ' . $cat . '</option>';
}
$categorySelector .= '</select><input type="submit" value="Send"></form>';

function showCategoriesSelector($categoryList, $categoryNumber, $item)
{
    $categorySelector = '<form method="get">
    <input type="hidden" value="' . $item . '" name="item" />
    <select name="update_cat" onchange="this.form.submit();">';
    foreach($categoryList as $key => $cat) {
        $condition = $categoryNumber == $key ? 'selected' : '';
        $categorySelector .= '<option value="' . $key . '" ' . $condition . '> ' . $cat . '</option>';
    }
    $categorySelector .= '</select></form>';
    return $categorySelector;
}

# сортировка массива на основе параметров запроса
switch (str_replace('-', '', $_REQUEST['sort'])) {
    case 'category':
        if($descStatus) {
            function mnsort($a, $b)
            {
                return strnatcmp($b['category'], $a['category']); // применение человекоподобного алгоритма для сортировки строк
            }
        } else {
            function mnsort($a, $b)
            {
                return strnatcmp($a['category'], $b['category']);
            }
        }
        break;
    default:
        if($descStatus) {
            function mnsort($a, $b)
            {
                $sortName = str_replace('-', '', $_REQUEST['sort']); // удаление лишнего символа для получения текущего поля сортировки
                return $b[$sortName] > $a[$sortName]; // сравнение числовых значений для сортировки
            }
        } else {
            function mnsort($a, $b)
            {
                return $a[$_REQUEST['sort']] > $b[$_REQUEST['sort']];
            }
        }
        break;
}
$sortOrder = 'arrow' . str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('-', '', $_REQUEST['sort'])))); // формирование имени переменной хранящей текущую стрелку
$$sortOrder = $arrow; // присвоение текущей стрелки
usort($arr, 'mnsort'); // применение сортировки инициализированной в switch функции
//include('template.html'); // подключение представления
# формирование переменной таблицы
$table = '
<style>
    select.filter, input{
        height: 30px;
        width: 200px;
        font-size: 20px;
    }
    .container {
        width: 100%;
        height: 100%;
        overflow: auto;
    }
    table, tr, th, td {
        text-align: center;
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }
    table {
        width: 100%;
    }
    thead th {
        line-height: 25px;
        position: sticky;
        top: -2px;
    }
    .leftText{
        text-align: left;
    }
    .leftRight{
        text-align: right;
    }
    tr:hover{
        background: aqua;
    }
    th{
        text-transform: uppercase;
        background: #ededed;
        color: #555;
    }
    th a{
        color: #000fbc;
        text-decoration: none;
        padding: 5px 10px;
    }
    th span{
        color: #555;
        font-size: 1.1em;
    }
    p,center {
        margin: 0 auto;
        width: 200px;
    }
</style>
<div class="container">
    <h1>Home Task</h1>
    ' . $categorySelector . '
    <table><thead><tr style="position: sticky;top: 0px;">
        <th><span>' . @$arrowId . '</span><a href="?sort=' . $descParam . 'id">id</a><span>' . @$arrowId . '</span></th>
        <th>user link</th>
        <th>comment</th>
        <th><span>' . @$arrowCategory . '</span><a href="?sort=' . $descParam . 'categoryName">category</a><span>' . @$arrowCategory . '</span></th>
        <th><span>' . @$arrowTotalSpent . '</span><a href="?sort=' . $descParam . 'total_spent">total spent, usd</a><span>' . @$arrowTotalSpent . '</span></th>
        <th><span>' . @$arrowCreatedAt . '</span><a href="?sort=' . $descParam . 'created_at">created at</a><span>' . @$arrowCreatedAt . '</span></th>
    </tr></thead>';
foreach($arr as $row) {
    if($_REQUEST['search'] && $_REQUEST['search'] != $row['category']) continue;
    $categorySelector = showCategoriesSelector($category, $row['category'], $row['id']);
    # форматирование значений перед записью в переменную таблицы
    $table .= '<tr>
            <td class="leftRight" title="' . strlen($row['id']) . ' symbols">' . $row['id'] . '</td>
            <td title="' . strlen($row['user_link']) . ' symbols">' . $row['user_link'] . '</td>
            <td class="leftText" title="' . strlen($row['comment']) . ' symbols">' . mb_strimwidth($row['comment'], 0, 100, "...") . '</td>
            <td title="' . strlen($row['categoryName']) . ' symbols">' . $categorySelector . '</td>
            <td title="' . (strlen($row['total_spent']) + 1) . ' symbols">$' . $row['total_spent'] . '</td>
            <td title="' . strlen(date('Y-m-d', $row['created_at'])) . ' symbols">' . date('Y-m-d', $row['created_at']) . '</td>
        </tr></div>';
}
$table .= '<tr>
    <th><span>' . @$arrowId . '</span><a href="?sort=' . $descParam . 'id">id</a><span>' . @$arrowId . '</span></th>
    <th>user link</th>
    <th>comment</th>
    <th><span>' . @$arrowCategory . '</span><a href="?sort=' . $descParam . 'categoryName">category</a><span>' . @$arrowCategory . '</span></th>
    <th><span>' . @$arrowTotalSpent . '</span><a href="?sort=' . $descParam . 'total_spent">total spent</a><span>' . @$arrowTotalSpent . '</span></th>
    <th><span>' . @$arrowCreatedAt . '</span><a href="?sort=' . $descParam . 'created_at">created at</a><span>' . @$arrowCreatedAt . '</span></th>
</tr></table>';
echo $table, '<p class="center">Array size: ' . sizeof($arr) . ' elements</p>'; // вывод таблицы