<?php
/**
 * Created by PhpStorm.
 * User: chancellor
 * Date: 02.12.18
 * Time: 19:05
 */

if (!empty($_REQUEST['action'])) { // проверка наличия параметра action в запросе
    if ($_REQUEST['action'] == 'auth') { // если action == auth
        include_once('../modules/auth.inc.php'); // подключаю модуль авторизации
    } elseif ($_REQUEST['action'] == 'sign_up') { // если action == sign_up
        include_once('../modules/sign_up.inc.php'); // подключаю модуль регистрации
    }
}

if (empty($_SERVER['REDIRECT_URL']) || $_SERVER['REDIRECT_URL'] == '/') { // проверяю на какую страницу отправляется запрос
    if (empty($_SESSION['session_hash'])
        || empty($_COOKIE['session_hash'])
        || $_SESSION['session_hash'] != $_COOKIE['session_hash']) { // если пользователь не авторизирован
        include_once('../modules/auth.inc.php'); // подключаю модуль авторизации
    } else {
        include_once('../modules/json_table.inc.php'); // если сессия существует перенаправляю пользователя на страницу таблицы
    }
} elseif ($_SERVER['REDIRECT_URL'] == '/sign_up') { // если ссылка ведет на страницу регистрации
    include_once('../modules/sign_up.inc.php'); // подключаю модль регистрации
} elseif ($_SERVER['REDIRECT_URL'] == '/logout') { // если пользователь нажал кнопку logout
    setcookie('session_hash', '', 0); // уничтожаю кукис
    header('Location: /'); // перенаправляю на главную
} elseif ($_SERVER['REDIRECT_URL'] == '/profile') { // если пользователь перешел на страницу профиля
    include_once('../modules/profile.inc.php'); // подключаю модуль профиля
}
