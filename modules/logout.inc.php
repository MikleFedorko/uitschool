<?php

setcookie('session_hash', '', 0); // уничтожаю кукис
header('Location: /'); // перенаправляю на главную