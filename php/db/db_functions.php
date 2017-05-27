<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 21/02/2017
 * Time: 22:39
 */
include('connection.php');
//error_reporting(0);
class DbFunctions
{
     static $count = 0;

     function __construct()
     {
//        self::$count++;
//        echo self::$count;
     }

    //save all urls available on page
    public static function saveUrl($url){
        global $connection;
        $query = "INSERT INTO links(url) VALUES ('$url')";
        $result = mysqli_query($connection, $query);

        if (!$result)
        {
            die('Problem inserting date into DB' . mysqli_error);
        }
    }

    //save date when info was last time updated
    public static function saveDate($dateArray)
    {
        global $connection;
        $date = $dateArray['date'];
        //time maybe will be useful in the future
        //$time = $dateArray['time'];
        $query = "INSERT INTO last_update (date)
            SELECT * FROM (SELECT '$date') AS tmp6
            WHERE NOT EXISTS (
                SELECT date FROM last_update WHERE date = '$date'
            ) LIMIT 1;";

        if ($connection->query($query)) {
            if ($connection->affected_rows > 0)
            {
//                printf("Select returned %d rows.\n", $connection->affected_rows);
//                $connection->close();
                return true;
            }
            else {
//                printf("Closing. Select returned %d rows. This date is already in the DB \n", $connection->affected_rows);
//                $connection->close();
                return false;
            }
        } else {
            return false;
            $connection->close();
        }
    }

    //for saving civilprocess
    public static function saveCivilprocess($dataArray)
    {
        global $connection;
        $data = "'" . implode("','", $dataArray) . "'";
        if(is_array($dataArray))
        {
            $query = "INSERT INTO civilprocess (" . implode(',', array_keys($dataArray)) . ") values (". $data .")";
            print_r($query);
            die();
//            $result = mysqli_real_escape_string($connection, $query);
            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                die('Problem inserting date into DB </br>' . mysqli_error($connection));
                $connection->close();
            }
        }
    }

    //for saving KRIMINALPROCESS
    public static function saveKriminalprocess($dataArray)
    {
        global $connection;
        $data = "'" . implode("','", $dataArray) . "'";
        if(is_array($dataArray))
        {
            $query = "INSERT INTO civilprocess (" . implode(',', array_keys($dataArray)) . ") values (". $data .")";
//            print_r($query);
//            die();
//            $result = mysqli_real_escape_string($connection, $query);
            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                die('Problem inserting date into DB </br>' . mysqli_error($connection));
            }
        }
    }

    //for saving ADMINISTRATIVAIS PROCESS
    public static function saveAdministrativaisProcess($dataArray)
    {
        global $connection;
        $data = "'" . implode("','", $dataArray) . "'";
        if(is_array($dataArray))
        {
            $query = "INSERT INTO civilprocess (" . implode(',', array_keys($dataArray)) . ") values (". $data .")";
//            print_r($query);
//            die();
//            $result = mysqli_real_escape_string($connection, $query);
            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                die('Problem inserting date into DB </br>' . mysqli_error($connection));
            }
        }
    }

    //for saving ADMINISTRATIVAIS PARKAPUMA PROCESS
    public static function saveAdmnistrativaParkapumaProcess($dataArray)
    {
        global $connection;
        $data = "'" . implode("','", $dataArray) . "'";
        if(is_array($dataArray))
        {
            $query = "INSERT INTO civilprocess (" . implode(',', array_keys($dataArray)) . ") values (". $data .")";
//            print_r($query);
//            die();
//            $result = mysqli_real_escape_string($connection, $query);
            $result = mysqli_query($connection, $query);

            if (!$result)
            {
                die('Problem inserting date into DB </br>' . mysqli_error($connection));
            }
        }
    }




























}