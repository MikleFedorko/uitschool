<?php
/**
 * Created by PhpStorm.
 * User: chancellor
 * Date: 02.12.18
 * Time: 18:41
 */

if (!empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'auth') {
        include_once('../modules/auth.inc.php');
    } elseif ($_REQUEST['action'] == 'sign_up') {
        include_once('../modules/sign_up.inc.php');
    }
}