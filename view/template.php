<?php

$categorySelector = '<div class="float-right">
    <input value="Hello, ' . $user['user_name'] . '!" disabled  class="btn btn-classic" style="display: inline-block; width: 200px" />
    <a class="btn btn-primary pull-right" href="/profile">Profile</a>
    <a class="btn btn-primary pull-right" href="/logout">Logout</a>
</div>
<div class="form-group">
<form method="get"><select name="search" class="filter btn"><option value="">All</option>';
foreach ($categories as $key => $cat) {
    if (isset($_REQUEST['search'])) {
        $condition = $_REQUEST['search'] == $key ? 'selected' : '';
    }
    $categorySelector .= '<option value="' . $key . '" ' . @$condition . '> ' . $cat . '</option>';
}
$categorySelector .= '</select><input class="btn btn-success" type="submit" value="Send"></form></div>';

# формирование переменной таблицы
$content = '
<div class="table_container">
    ' . $categorySelector . '
    <table><thead><tr style="position: sticky;top: 0px;">
            <th><span>' . @$arrowId . '</span><a href="?sort=' . $descParam . 'id">id</a><span>' . @$arrowId . '</span></th>
            <th>user link</th>
            <th>comment</th>
            <th><span>' . @$arrowCategoryName . '</span><a href="?sort=' . $descParam . 'categoryName">category</a><span>' . @$arrowCategoryName . '</span></th>
            <th><span>' . @$arrowTotalSpent . '</span><a href="?sort=' . $descParam . 'total_spent">total spent, usd</a><span>' . @$arrowTotalSpent . '</span></th>
            <th><span>' . @$arrowCreatedAt . '</span><a href="?sort=' . $descParam . 'created_at">created at</a><span>' . @$arrowCreatedAt . '</span></th>
            <th>Tags</th>
            <th>Actions</th>
        </tr></thead>';


foreach ($userRequest as $row) {
    if (!empty($_REQUEST['search']) && $_REQUEST['search'] != $row['category']) continue;
    if (strpos($user['roles'], 'admin')) {
        $categorySelector = showCategoriesSelector($categories, $row['category'], $row['id']); // формирование селектора категорий для текущей строки
    } else {
        $categorySelector = $row['categoryName'];
    }

    # форматирование значений перед записью в переменную таблицы
    $content .= '<tr>
            <td class="leftRight" title="' . strlen($row['id']) . ' symbols">' . $row['id'] . '</td>
            <td title="' . strlen($row['user_link']) . ' symbols">' . $row['user_link'] . '</td>
            <td class="leftText" title="' . strlen($row['comment']) . ' symbols">' . mb_strimwidth($row['comment'], 0, 100, "...") . '</td>
            <td title="' . strlen($row['categoryName']) . ' symbols">' . $categorySelector . '</td>
            <td title="' . (strlen($row['total_spent']) + 1) . ' symbols">$' . $row['total_spent'] . '</td>
            <td title="' . strlen(date('Y-m-d', $row['created_at'])) . ' symbols">' . date('Y-m-d', $row['created_at']) . '</td>
            <td>' . $row['tagNames'] . '</td>
            <td><a class="btn btn-danger" href="/delete?item=' . $row['id'] . '">Delete</a></td>
        </tr></div>';
}
$content .= '<tr>
    <th><span>' . @$arrowId . '</span><a href="?sort=' . $descParam . 'id">id</a><span>' . @$arrowId . '</span></th>
    <th>user link</th>
    <th>comment</th>
    <th><span>' . @$arrowCategoryName . '</span><a href="?sort=' . $descParam . 'categoryName">category</a><span>' . @$arrowCategoryName . '</span></th>
    <th><span>' . @$arrowTotalSpent . '</span><a href="?sort=' . $descParam . 'total_spent">total spent</a><span>' . @$arrowTotalSpent . '</span></th>
    <th><span>' . @$arrowCreatedAt . '</span><a href="?sort=' . $descParam . 'created_at">created at</a><span>' . @$arrowCreatedAt . '</span></th>
    <th>Tags</th>
    <th>Actions</th>
</tr></table>';
//echo $content, '<p class="center">Array size: ' . sizeof($arr) . ' elements</p>'; // вывод таблицы

require_once('../view/layout.php');
