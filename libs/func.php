<?php

/**
 * @param array $categoryList
 * @param int $categoryNumber
 * @param int $item
 * @return string
 */
function showCategoriesSelector(array $categoryList, int $categoryNumber, int $item)
{
    $categorySelector = '<form method="get">
    <input type="hidden" value="' . $item . '" name="item" /> <!-- скрытое поле с id элемента -->
    <select name="update_cat" onchange="this.form.submit();">';
    foreach ($categoryList as $key => $cat) {
        $condition = $categoryNumber == $key ? 'selected' : '';
        $categorySelector .= '<option value="' . $key . '" ' . $condition . '> ' . $cat . '</option>';
    }
    $categorySelector .= '</select></form>';

    return $categorySelector;
}

/**
 * @param array $arr
 * @param bool $descStatus
 * @return array
 */
function sorter(array $arr, bool $descStatus)
{
    if (!isset($_REQUEST['sort'])) return $arr;

    # сортировка массива на основе параметров запроса
    switch (str_replace('-', '', $_REQUEST['sort'])) {
        case 'category':
            if ($descStatus) {
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
            if ($descStatus) {
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
    usort($arr, 'mnsort'); // применение сортировки инициализированной в switch функции

    return $arr;
}

/**
 * @param string $filename
 * @return string
 */
function freader(string $filename)
{
    if (!$fp = fopen($filename, 'r')) {
        echo "Не могу открыть файл ($filename)";
        exit;
    }
    $mytext = ''; // пустая переменная для записи данных из файла
    while (!feof($fp)) { // проверка что указатель файла не достиг End Of File (EOF)
        $mytext .= fgets($fp); // функция берет чанк символо узаканной длинный из файла
    }
    fclose($fp); // закрывает поток

    return $mytext;
}

/**
 * @param string $filename
 * @param array $arr
 * @return void
 */
function fwriter(string $filename, array $arr)
{
    if (!$handle = fopen($filename, 'w')) {
        echo "Не могу открыть файл ($filename)";
        exit;
    }
    if (fwrite($handle, json_encode($arr, JSON_PRETTY_PRINT)) === FALSE) {
        echo "Не могу произвести запись в файл ($filename)";
        exit;
    }
    fclose($handle);
}