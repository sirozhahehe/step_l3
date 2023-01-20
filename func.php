<?php
     function getMax($a, $b) {

        return max($a, $b);
    }

    function find(string $key) {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return null;
    }

function findAndDelete(string $key): mixed
{
    if (!isset($_SESSION[$key])) {
        return null;
    }
    
    $result = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $result;
}
function findInSession(string $key): mixed
{
    if (!isset($_SESSION[$key])) {
        return null;
    }
    
    return $_SESSION[$key];
}
