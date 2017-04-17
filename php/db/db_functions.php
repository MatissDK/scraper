<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 21/02/2017
 * Time: 22:39
 */
include('connection.php');
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
            if($connection->affected_rows>0)
            {
                print_r('Do something else . <br>');
                printf("Select returned %d rows.\n", $connection->affected_rows);
                $connection->close();
            }
            else {
                printf("Closing. Select returned %d rows.\n", $connection->affected_rows);
                $connection->close();
            }
        } else {
            $connection->close();
        }
    }
}