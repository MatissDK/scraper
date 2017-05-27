<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 21/02/2017
 * Time: 22:22
 */

$connection = new mysqli('127.0.0.1:3306','root','','scraper');
$connection->set_charset("utf8");
if ($connection->connect_errno) {
    printf("Connect failed: %s\n", $connection->connect_error);
    exit();
}