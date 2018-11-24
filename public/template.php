<?php

$categorySelector = '<form method="get"><select name="search" class="filter">';
foreach ($category as $key => $cat) {
    if (isset($_REQUEST['search'])) {
        $condition = $_REQUEST['search'] == $key ? 'selected' : '';
    }
    $categorySelector .= '<option value="' . $key . '" ' . @$condition . '> ' . $cat . '</option>';
}
$categorySelector .= '</select><input type="submit" value="Send"></form>';

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


foreach ($arr as $row) {
    if (isset($_REQUEST['search']) && $_REQUEST['search'] != $row['category']) continue;

    $categorySelector = showCategoriesSelector($category, $row['category'], $row['id']); // формирование селектора категорий для текущей строки

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