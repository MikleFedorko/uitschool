<?php

if (!empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
    $email = strip_tags($_REQUEST['email']);
    $result = $conn->query('select * 
from users 
where email = "' . $email . '" 
and user_password = "' . md5($_REQUEST['password']) . '"
    ');
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        auth($conn, $user['id']);
    } else {
        $errorMessage .= 'Wrong email or password!';
    }
}

include_once('../view/auth.php');
