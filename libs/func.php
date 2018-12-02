<?php

/**
 * @param $filename
 * @return array
 */
function getEnvs($filename)
{
    $envFile = file_get_contents($filename);
    $envData = explode('
', $envFile);
    $env = [];
    foreach ($envData as $param) {
        $item = explode('=', $param);
        $env[trim($item[0])] = trim($item[1]);
    }

    return $env;
}

/**
 * @param $conn
 * @param int $id
 */
function auth($conn, $id)
{
    $current_time = time();
    $hash = base64_encode(substr($current_time, 0, strlen($current_time) - strlen($id)) . $id . '1984' . strlen($id));
    $_SESSION['session_hash'] = $hash;
    setcookie('session_hash', $hash);
    $conn->query('update users set last_login = "' . $current_time . '" where id = ' . $id);
}

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
 * @param $conn
 * @param int $item
 * @param int $update_cat
 */
function changeCategory($conn, int $item, int $update_cat)
{
    $conn->query('update user_request set category = "' . $update_cat . '" where id = ' . $item);
    header('Location: /');
}

/**
 * @return bool|string|void
 */
function getUserId()
{
    if(empty($_COOKIE['session_hash'])) return;
    $session_hash = explode('1984', base64_decode($_COOKIE['session_hash']));
    return substr($session_hash[0], strlen($session_hash[0]) - $session_hash[1]);;
}

/**
 * @param $conn
 * @param $userId
 * @return mixed
 */
function getUserData($conn, $userId)
{
    $userData = $conn->query('select * from users where id = ' . $userId);
    return $userData->fetch_assoc();
}