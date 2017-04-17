<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 22/02/2017
 * Time: 08:06
 */
require('../libs/simplehtmldom_1_5/simple_html_dom.php');
require 'db/db_functions.php';
require 'helper_functions.php';

class ScraperLogic{

    //static $test;

    const URL = 'http://tis.ta.gov.lv/court.jm.gov.lv/stat/html/index_201704.html';

    const PART_URL = 'http://tis.ta.gov.lv/court.jm.gov.lv/stat/html/';

//    public function showConstant(){
//        echo self::URL;
//    }

    //gets all URL from main page
    public static function getUrl(){
        // Create DOM from URL or file
        $html = file_get_html(self::URL);
        // Find all links
        foreach ($html->find('a') as $element)
        {
            $link = self::PART_URL . $element->href;
            echo '<a href="'.$link.'">'.$link.'</a>';
            echo '</br>';
            //DbFunctions::saveUrl($link);
        }
        echo 'Link are saved </br>';
    }


    //if last update date is not into DB insert value; otherwise don't
    public static function getDate()
    {
        $html = file_get_html(self::URL);
        // Find all links
        foreach ($html->find('div[class=info]') as $element) {
            //DbFunctions::saveDate(HelperFunctions::getDate($element->plaintext));
            HelperFunctions::getTime($element);
          //  print_r('asdasd');
        }
    }


}








