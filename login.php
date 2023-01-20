<?php
session_start();
require_once('func.php');
require_once('db.php');

$username = find('username');
$password = find('password');

try {
    authenticate($username, $password);
} catch (\Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

function authenticate(?string $username, ?string $password)
{
    if (!$username || !$password) {
        throw new \Exception('Заполните все поля!');
    }
    if (!$user = findUser($username)) {
        throw new \Exception('Пользователь не найден!');
    }
    if (!password_verify($password, $user['password'])) {
        throw new \Exception('Пароль не верен!');
    }
    unset($user['password']);
    $_SESSION['user'] = $user;
}


header('Location: index.php');