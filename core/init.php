<?php
$db = mysqli_connect('127.0.0.1', 'root', '', 'diplom');
if(mysqli_connect_errno()) {
    echo 'Database connection faild with following errors: '. mysqli_connect_error();
    die();
}
require_once '../config.php';
require_once BASEURL.'helpers/helpers.php';