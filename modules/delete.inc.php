<?php

if($_REQUEST['item']) {

    $sql = 'delete from user_request where id = ' . (int)$_REQUEST['item'];
//    print_r($sql);die;
    if($conn->query($sql)){
        header('Location: /');
    } else {
        echo mysqli_error($conn);
    }
}
