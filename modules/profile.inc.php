<?php

$name = '';
$destination = '/avatars/no_avatar.png';

$sql = 'select user_name, avatar_src from users where id = ' . $userId;
$userData = $conn->query($sql);
$user = $userData->fetch_assoc();
if ($conn->error) {
    print_r($conn->error);
    die;
}
$name = $user['user_name'];
if ($user['avatar_src']) $destination = $user['avatar_src'];

if (!empty($_FILES) && empty($_REQUEST['name'])) {
    try {

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['avatar']['error']) ||
            is_array($_FILES['avatar']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $_FILES['avatar']['error'] value.
        switch ($_FILES['avatar']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here.
        if ($_FILES['avatar']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES['avatar']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
                $finfo->file($_FILES['avatar']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
            throw new RuntimeException('Invalid file format.');
        }

        $sql = 'select avatar_src from users where id = ' . $userId;
        $fileData = $conn->query($sql);
        $file = $fileData->fetch_assoc();
        $path = '../public/' . $file['avatar_src'];
        if ($file['avatar_src'] && file_exists($path)) unlink($path);

        // You should name it uniquely.
        // DO NOT USE $_FILES['avatar']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $destination = sprintf('../public/avatars/%s.%s',
            sha1_file($_FILES['avatar']['tmp_name']),
            $ext
        );
        if (!move_uploaded_file(
            $_FILES['avatar']['tmp_name'],
            $destination
        )) {
            $destination = false;
            throw new RuntimeException('Failed to move uploaded file.');
        }

        $destination = str_replace('../public/', '', $destination);
        $conn->query('update users set avatar_src = "' . $destination . '" where id = ' . $userId);
        if ($conn->error) {
            print_r($conn->error);
            die;
        } else {
            header('Content-Type: application/json');
            print_r("'" . json_encode(["path" => $destination]) . "'");
            die;
        }
    } catch (RuntimeException $e) {
        $destination = false;
//        echo $e->getMessage();
    }
}

if (!empty($_REQUEST['name'])) {
    $name = strip_tags($_REQUEST['name']);
    $conn->query('update users set user_name = "' . $name . '" where id = ' . $userId);
    if ($conn->error) {
        print_r($conn->error);
        die;
    }
}

include_once('../view/profile.php');
