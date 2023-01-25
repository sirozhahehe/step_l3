<?php

$_connection = null;

function getConnection(): bool|mysqli|null
{
    global $_connection;

    if (!$_connection) {
        $_connection = mysqli_connect('localhost', 'root');
        mysqli_select_db($_connection, 'new_bd');
    }

    return $_connection;
}

function register(string $username, string $password): void
{
    $statement = mysqli_prepare(
        getConnection(),
        "INSERT INTO users (username, password) VALUES (?, ?);",
    );
    $password = crypt($password, 'randomSalt');
    mysqli_stmt_bind_param($statement, 'ss', $username, $password);
    mysqli_stmt_execute($statement);
}

function findUser(string $username, bool $isFull = false): bool|array|null
{
    $columns = $isFull ? '*' : 'id, username, password';
    $statement = mysqli_prepare(
        getConnection(),
        "SELECT $columns FROM users WHERE username = ?",
    );
    mysqli_stmt_bind_param($statement, 's', $username);
    mysqli_stmt_execute($statement);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
}


function updateUser(User $user)
{
    $query = "UPDATE users SET ";

    foreach ($user as $column => $value) {
        if ($column === 'id') {
            continue;
        }
        $query .= "$column = '$value', ";
    }
    $query = trim($query, ', ');
    $query .= " WHERE id = $user->id;";

    mysqli_query(getConnection(), $query);
}

function findUsers(): array
{
    $result = mysqli_query(getConnection(), 'SELECT * FROM users');
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function batchUsersInsert(array $users)
{
    $query = "INSERT INTO users (";
    $userKeys = current($users);
    unset($userKeys['id']);
    $query .= implode(', ', array_keys($userKeys));
    $query .= ') VALUES ';
    while ($users) {
        $user = array_shift($users);
        unset($user['id']);
        $query .= "('" . implode("', '", $user) . "'),";
    }
    $query = trim($query, ', ');
    mysqli_query(getConnection(), $query);
}
