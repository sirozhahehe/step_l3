<?php
    require_once('func.php');
    require_once('db.php');

    register(find('username'), find('password'));
    header("Location: index.php");