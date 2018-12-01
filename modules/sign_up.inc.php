<?php

if (!empty($_REQUEST['email'])
    && !empty($_REQUEST['password'])
    && !empty($_REQUEST['confirm'])
) {
    $email = strip_tags($_REQUEST['email']);
    $isEmail = $conn->query('select id from users where email = "' . $email . '"');
    if ($isEmail->num_rows > 0) {
        $errorMessage .= 'Such email is already taken!';
    }

    if (!$errorMessage) {
        if (!$errorMessage && $_REQUEST['password'] == $_REQUEST['confirm']) {
            $password = md5($_REQUEST['password']);
            $current_time = time();
//            $conn->query('insert into users (email, user_password, created_at, last_login)
//values ("' . $email . '", "' . $password . '", ' . $current_time . ', ' . $current_time . ')');
            $conn->query('insert into users (email, user_password) values ("' . $email . '", "' . $password . '")');
            $userResource = $conn->query('select id from users where email = "' . $email . '"');
            $user = $userResource->fetch_assoc();
            auth($conn, $user['id']);
        } else {
            $errorMessage .= 'Passwords not equal!';
        }
    }
}

include_once('../view/sign_up.php');
