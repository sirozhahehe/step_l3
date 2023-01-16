<?php
require_once('func.php');
require_once('db.php');

$user = findUser(find('username'));
$password = find('password');