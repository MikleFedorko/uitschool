<?php

echo '<!DOCTYPE HTML>
<html>
<head>
    <title>Home Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css?t=' . time() . '">
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <meta charset="utf-8">
</head>
<body>
<div class="layout">
    ' . $content . '
</div>
</body>
</html>';