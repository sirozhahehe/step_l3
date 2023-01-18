<?php
    session_start();
    require_once('func.php');
    require_once('db.php');

    $username = find('username');
    $password = find('password');

    if ($password && $username) {
        register($username, $password);
    } else {
        $_SESSION['error'] = 'Нужно заполнить все поля!';
    }

    header('Location: index.php');