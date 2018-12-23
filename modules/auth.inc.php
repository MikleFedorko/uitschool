<?php

if (!empty($_REQUEST['email']) && !empty($_REQUEST['password'])) { // проверка что в запросе пришли и логин и пароль
    $email = strip_tags($_REQUEST['email']); // удаляю теги из логина
    $result = $conn->query('select * from users where email = "' . $email . '" and user_password = "' . md5($_REQUEST['password']) . '"'); // проверяю наличие пользователя с переданными параметрами в базе
    if ($result->num_rows > 0) { // если количество полученых строк больше 0
        $user = $result->fetch_assoc(); // полученные данные в виде массива записываю в переменную
        auth($conn, $user['id']); // вызываю функцию авторизации
        echo json_encode(['path' => '/profile']);
    } else {
        echo json_encode(['error' => 'Wrong email or password!']);
    }
} else {
    include_once('../view/auth.php'); // подключаю представление
}
