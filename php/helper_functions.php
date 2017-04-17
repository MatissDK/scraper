<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 23/02/2017
 * Time: 00:00
 */

class HelperFunctions{

    //extract clean date for saving in DB and return array
    public static function getDate($dateString){
        //explode string into array
        $words = explode (' ', $dateString);
        //get rid of first array elem. and last
        $keep_date = array_slice($words, 5,-4);
        $keep_time = array_slice($words, 7,-2);
        $arrayOut = array(
            "date"=>$string_keep_date = implode(" ", $keep_date),
            "time"=> $string_keep_time = implode(" ", $keep_time)
        );
        return $arrayOut;
    }

    //extract clean time to save in DB
    public static function getTime($dateString){
        $words = explode (' ', $dateString);
        //takes one element in the time and returns it
        $lastElement = array_pop($words);
        $lastElement2 = array_pop($words);
        $lastElement3 = array_pop($words);
        print_r($lastElement3);
    }

}