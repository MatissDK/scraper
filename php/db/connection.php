<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 21/02/2017
 * Time: 22:22
 */


//require dirname(__FILE__,2). '\helper_functions.php';

class DBconnection {

    public static function connection(){

//        echo dirname(__FILE__,2). '\helper_functions.php';
//        die();
        $connection = new mysqli('127.0.0.1:3306','root','','scraper');
        $connection->set_charset("utf8");
        if ($connection->connect_errno) {
            printf("Connect failed: %s\n", $connection->connect_error);
            exit();
        }
        return $connection;
    }

}


