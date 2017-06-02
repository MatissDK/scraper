<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 23/02/2017
 * Time: 00:00
 */

class HelperFunctions {

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

    //analyze which process/instance is it
    public static function getCourtInfo($dom)
    {
        $courtInfo = [];
        foreach ($dom->find('div[class=courtinfo] span') as $key=>$element)
        {
            $key == 0 ? $key = 'tiesa' : $key = 'instance';
            //replace multiple spaces with a single space
            $courtInfo[$key] = preg_replace('!\s+!', ' ',$element->plaintext);
        }
        return $courtInfo;
    }

    //index_201705.html
    public static function getCurrentMonthUrl(){
        $url = 'http://tis.ta.gov.lv/court.jm.gov.lv/stat/html/index_'. date('Ym').'.html';
        return $url;
    }

    //returns just instance of the court for filtering purposes
    //in index position 0
    public static function court($dom)
    {
        if(empty($dom) || $dom == null)
        {
            return;
        }

        foreach ($dom->find('div[class=courtinfo] span') as $key=>$element)
        {
            if($key == 1)
            {
                $wordsArray = explode(' ', $element->plaintext);
            }
        }

        if($wordsArray[0] == 'Administratīvais')
        {
            $outValue = $wordsArray[0] . ' ' . $wordsArray[1];
            return $outValue;
        }
        else
        {
            return $wordsArray[0];
        }

    }

    //displaying log info

    public static function createLog($data)
    {
        if(is_string($data))
        {
            $myLogfile = fopen('logs/' .date('d_m_y').'.txt'  , 'a') or die();
            fwrite($myLogfile, $data);
            fclose($myLogfile);
        }
    }




    //pre tags for formatting
    public static function preTag($input)
    {
        echo '<pre>';
        var_dump($input);
        echo '</pre>';
    }


}