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
    const Civilprocess  = 1;
    const Kriminalprocess = 2;
    const Administrativais_process = 3;
    const Administrativais_parkapuma_process = 4;

    private $html;

    function __construct()
    {
        // Create DOM from URL or file
        $this->html = file_get_html(HelperFunctions::getCurrentMonthUrl());
    }

    //gets DOM of page links
    public static function getDOM($url)
    {
        $dom = file_get_html($url);
        return $dom;
    }

    //gets all URL from main page
    public function getUrl(){

        if(!$this->getDate())
        {
            // Find all links
            foreach ($this->html->find('a') as $element)
            {
                if (self::filterUrl($element))
                {
                    $link = self::PART_URL . $element->href;
                    //$this->getRowInfo(self::getDOM($link));
                    $this->filterByCourt(self::getDOM($link));
                }
            }
            echo 'Links are saved';
        }
        else
        {
            echo 'Links are NOT saved. </br> Data is already in the DB.</br>';
        }
    }

    //only save url which are links for court page
    private static function filterUrl($url)
    {
        if(empty($url) || $url == null)
        {
            return;
        }

        if(!preg_match('/index/',$url) && !preg_match('/cal/',$url))
        {
            return true;
        }
    }

    //check which cour is it so could be invoked appropriate function
    private function filterByCourt($dom)
    {
        if(empty($dom) || $dom == null)
        {
            return;
        }

        switch (HelperFunctions::court($dom)){
            case 'Civilprocess':
                self::getRowInfo($dom, self::Civilprocess);
                break;
            case 'Kriminālprocess':
                self::getRowInfo($dom, self::Kriminalprocess);
                break;
            case 'Administratīvais process':
                self::getRowInfo($dom, self::Administrativais_process);
                break;
            case 'Administratīvais pārkāpuma':
                self::getRowInfo($dom, self::Administrativais_parkapuma_process);
                break;
        }
    }


    public function getRowInfo($dom, $court)
    {
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

                   if ($court == self::Civilprocess)
                   {
                       $tableInfo[] = self::analizeRowCivilprocess($cell,$key);
                   }
                   else if ($court == self::Kriminalprocess)
                   {
                       $tableInfo[] = self::analizeRowKriminalprocess($cell,$key);
                   }
                   else if ($court == self::Administrativais_process)
                   {
                       $tableInfo[] = self::analizeRowAdministrativais($cell,$key);
                   }
                   else if ($court == self::Administrativais_parkapuma_process)
                   {
                       $tableInfo[] = self::analizeRowAPK($cell,$key);
                   }

               }
               else
               {
                   $key = $key + $cell->colspan;
               }
           }

           if ($court == self::Civilprocess)
           {
               DbFunctions::saveCivilprocess(self::clearArray($tableInfo, $dom));
           }
           else if ($court == self::Kriminalprocess)
           {
               DbFunctions::saveKriminalprocess(self::clearArray($tableInfo, $dom));
           }
           else if ($court == self::Administrativais_process)
           {
               DbFunctions::saveAdministrativaisProcess(self::clearArray($tableInfo, $dom));
           }
           else if ($court == self::Administrativais_parkapuma_process)
           {
               DbFunctions::saveAdmnistrativaParkapumaProcess(self::clearArray($tableInfo, $dom));
           }
           unset($tableInfo);
       }
    }

    private static function analizeRowCivilprocess($cell, $key)
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

    private static function analizeRowKriminalprocess($cell, $key)
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
                $array_key = 'apsudzetais';
                break;
            case 6:
                $array_key = 'apsudzibas_panti';
                break;
            case 7:
                $array_key = 'veids';
                break;
            case 8:
                $array_key = 'tiesnesis';
                break;
        }

        return array(
            $array_key => preg_replace("/&#?[a-z0-9]+;/i","",$cell->plaintext)
        );
    }

    private static function analizeRowAPK($cell, $key)
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
                $array_key = 'persona';
                break;
            case 6:
                $array_key = 'apsudzibas_panti';
                break;
            case 7:
                $array_key = 'veids';
                break;
            case 8:
                $array_key = 'tiesnesis';
                break;
        }

        return array(
            $array_key => preg_replace("/&#?[a-z0-9]+;/i","",$cell->plaintext)
        );
    }

    private static function analizeRowAdministrativais($cell, $key)
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
                $array_key = 'pieteicejs';
                break;
            case 6:
                $array_key = 'atbildetajs';
                break;
            case 7:
                $array_key = 'prasijums';
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
    public static function clearArray($rowArray, $dom)
    {
        if(empty($rowArray) || $rowArray == null || !is_array($rowArray))
        {
            return;
        }

        $filteredArray = [];

            foreach ($rowArray as $value) {
                foreach ($value as $key => $mainValue) {
                    $filteredArray [$key] = $mainValue;
                }
            }

        //adding court and instance info
        // TODO ---> make more elegant solution
        $infoArray[] = HelperFunctions::getCourtInfo($dom);
        $filteredArray['tiesa'] = $infoArray[0]['tiesa'];
        $filteredArray['instance'] = $infoArray[0]['instance'];

//        HelperFunctions::preTag($filteredArray);
//        die();

        return $filteredArray;
        }

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

}





