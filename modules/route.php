<?php
/**
 * Created by PhpStorm.
 * User: chancellor
 * Date: 02.12.18
 * Time: 19:05
 */

if (empty($_SERVER['REDIRECT_URL']) || $_SERVER['REDIRECT_URL'] == '/') {
    if (empty($_SESSION['session_hash'])
        || empty($_COOKIE['session_hash'])
        || $_SESSION['session_hash'] != $_COOKIE['session_hash']) {
        include_once('../modules/auth.inc.php');
    } else {
        include_once('../modules/json_table.inc.php');
    }
} elseif ($_SERVER['REDIRECT_URL'] == '/sign_up') {
    include_once('../modules/sign_up.inc.php');
} elseif ($_SERVER['REDIRECT_URL'] == '/logout') {
    setcookie('session_hash', '', 0);
    header('Location: /');
} elseif ($_SERVER['REDIRECT_URL'] == '/profile') {
    include_once('../modules/profile.inc.php');
}
