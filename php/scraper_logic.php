<?php
/**
 * Created by PhpStorm.
 * User: matiss
 * Date: 22/02/2017
 * Time: 08:06
 */

require 'db/db_functions.php';
require 'helper_functions.php';
require('../libs/simplehtmldom_1_5/simple_html_dom.php');

class ScraperLogic {
    const PART_URL = 'http://tis.ta.gov.lv/court.jm.gov.lv/stat/html/';
    const URL1 = 'http://tis.ta.gov.lv/court.jm.gov.lv/stat/html/111_201705.html';

    private $html;
    private $page;

    function __construct()
    {
        // Create DOM from URL or file
        $this->html = file_get_html(HelperFunctions::getCurrentMonthUrl());
        $this->page = file_get_html(self::URL1);
    }

    //gets all URL from main page
    public function getUrl(){
        // Find all links
//        if($this->getDate())
//        {
            foreach ($this->html->find('a') as $element) {
                if (self::filterUrl($element)) {
                    $link = self::PART_URL . $element->href;
//                echo '<a href="'.$link.'">'.$link.'</a>';
//                echo '</br>';
                    //DbFunctions::saveUrl($link);
                    self::getRowInfo(self::getDOM($link));
                }
            }
            echo 'Link are saved </br>';
//        }
//        else
//        {
//            echo 'Links are NOT saved </br>';
//        }
    }

    //only save url which are links for court page
    private static function filterUrl($url)
    {
        if(!preg_match('/index/',$url) && !preg_match('/cal/',$url))
        {
            return true;
        }
    }

    public static function getDOM($url)
    {
        $dom = file_get_html($url);
        return $dom;
    }

    //get court info and instance
    public function getRowInfo($dom)
    {
       $arrayOfCourtInfo = HelperFunctions::getCourtInfo($dom);
       $tableInfo = [];
       $tableRowCount = 0;
       foreach ($dom->find('.calendar tbody tr') as $calendar)
       {
           $key = 0;
           $tableRowCount++;
           foreach ($calendar->find('td') as $cell)
           {
               if ($cell->plaintext == '' || $cell->plaintext == '&nbsp;'){
                   $cell->innertext = 'Nav pieejams';
               }
               if (!isset($cell->colspan))
               {
                   $key++;
                   $tableInfo[] = self::analizeRow($cell,$key);
               }
               else
               {
                   $key = $key + $cell->colspan;
               }
           }

//           if(HelperFunctions::getCourtInfo($dom)[0])
//           {
//
//           }
//           HelperFunctions::preTag(self::clearArray($tableInfo));
           HelperFunctions::preTag(HelperFunctions::getCourtInfo($dom)['instance']);
           //DbFunctions::saveCivilprocess(self::clearArray($tableInfo));
           unset($tableInfo);
       }
    }

    private static function analizeRow($cell, $key)
    {
        $array_key = '';
        switch ($key) {
            case 1:
                $array_key = 'procesa_veids';
                break;
            case 2:
                $array_key = 'laiks';
                break;
            case 3:
                $array_key = 'lietas_numurs';
                break;
            case 4:
                $array_key = 'arhiva_numurs';
                break;
            case 5:
                $array_key = 'prasitajs';
                break;
            case 6:
                $array_key = 'atbildetajs';
                break;
            case 7:
                $array_key = 'butiba';
                break;
            case 8:
                $array_key = 'veids';
                break;
            case 9:
                $array_key = 'tiesnesis';
                break;
        }

        return array(
            $array_key => preg_replace("/&#?[a-z0-9]+;/i","",$cell->plaintext)
        );
    }


    //need to clear array for easy saving in DB, because at raw format it's nested inside other array
    public static function clearArray($rowArray)
    {
        $filteredArray = [];
        if (is_array($rowArray) && !empty($rowArray))
        {
            foreach ($rowArray as $value) {
                foreach ($value as $key => $mainValue) {
                    $filteredArray [$key] = $mainValue;
                }
            }
            return $filteredArray;
        }
    }


    //testing for sending data to frontend
//    public static function getDOM(){
//        // Create DOM from URL or file
//        $html = ['a'=>[1,2,3,4],'b' => ['ccc'=>[1,2,3,4,5],2,3]];
//        //$html = file_get_html(self::URL);
////        $jsonArray = json_encode($html);
//        return $html;
//    }

    //if last update date is not into DB insert value; otherwise don't
    public function getDate()
    {
        $out = '';
        foreach ($this->html->find('div[class=info]') as $element) {
            if(DbFunctions::saveDate(HelperFunctions::getDate($element->plaintext))){
               $out = true;
            }
            else
                {
                   $out = false;
                }
        }
        return $out;
    }

    public function test()
    {
        echo '<pre>';
        var_dump(HelperFunctions::getCourtInfo($this->page));
        echo '</pre>';
//        foreach (HelperFunctions::getCourtInfo($this->page) as $key=>$lol){
//            echo $key . '</br>';
//        }
    }


}





