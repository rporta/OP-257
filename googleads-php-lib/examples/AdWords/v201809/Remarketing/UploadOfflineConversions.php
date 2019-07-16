<?php
/**
 * Copyright 2017 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\AdsApi\Examples\AdWords\v201809\Remarketing;

require __DIR__ . '/../../../../vendor/autoload.php';
//log
require_once '/var/script/pixelNotification/dev1/utils/xbug/xbug.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeed;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeedOperation;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeedService;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * This code example imports offline conversion values for specific clicks to
 * your account. To get Google Click ID for a click, run
 * CLICK_PERFORMANCE_REPORT.
 */
class UploadOfflineConversions
{

    const CONVERSION_NAME = 'INSERT_CONVERSION_NAME_HERE';
    const GCLID = 'INSERT_GOOGLE_CLICK_ID_HERE';
    const CONVERSION_TIME = 'INSERT_CONVERSION_TIME_HERE';
    const CONVERSION_VALUE = 'INSERT_CONVERSION_VALUE_HERE';

    public static $pathCsv = "/home/ramiro/Escritorio/data.csv"; 

    public static function runExample(
        AdWordsServices $adWordsServices,
        AdWordsSession $session,
        $conversionName,
        $gclid,
        $conversionTime,
        $conversionValue
    ) {
        xbug("runExample");
        $offlineConversionService = $adWordsServices->get($session, OfflineConversionFeedService::class);

        // Associate offline conversions with the existing named conversion tracker.
        // If this tracker was newly created, it may be a few hours before it can
        // accept conversions.
        $feed = new OfflineConversionFeed();
        $feed->setConversionName($conversionName);
        $feed->setConversionTime($conversionTime);
        $feed->setConversionValue($conversionValue);
        $feed->setGoogleClickId($gclid);

        // Optional: To upload fractional conversion credits, set the external
        // attribution model and credit. To use this feature, your conversion
        // tracker should be marked as externally attributed. See
        // https://developers.google.com/adwords/api/docs/guides/conversion-tracking#importing_externally_attributed_conversions
        // to learn more about importing externally attributed conversions.

        // $feed->setExternalAttributionModel('Linear');
        // $feed->setExternalAttributionCredit(0.3);

        $offlineConversionOperation = new OfflineConversionFeedOperation();
        $offlineConversionOperation->setOperator(Operator::ADD);
        $offlineConversionOperation->setOperand($feed);
        $offlineConversionOperations = [$offlineConversionOperation];

        $result = $offlineConversionService->mutate($offlineConversionOperations);
        $out = NULL;
        if(gettype($result) === "string"){
            $out = $result;
        }else{
            $feed = $result->getValue()[0];
            // xbug($result);
            $out = "OK";
        }
        xbug("result : {$out}");
        return $out;

    }
    /**
     * [main description]
     * @param  [String] $conversion_name  [description]
     * @param  [String] $gclid            [description]
     * @param  [String] $conversion_time  [description]
     * @param  [String] $conversion_value [description]
     * @return [String]                   [description] : "OK" | "No response" 
     */
    public static function main($conversion_name, $gclid, $conversion_time, $conversion_value)
    {
        xbug("main");
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->build();
        $out = self::runExample(
            new AdWordsServices(),
            $session,
            $conversion_name,
            $gclid,
            $conversion_time,
            floatval($conversion_value)
        );
        return $out;
    }

    public static function setPathCsv($pathCsv){
        self::$pathCsv = $pathCsv;
    }

    public static function getPathCsv(){
        return self::$pathCsv;
    }

    /**
     * RAMIRO PORTAS 
     * [processCsv description] : Se encarga de abrir un archivo csv, recorrer los registros y pasarlos por la funcion main
     * @return String [description] : "OK" | "No response" 
     */
    public static function processCsv($return = true){
        /*debe recorrer */
        // 
        xbug("processCsv");
        if (($fileCsv = fopen(self::getPathCsv(), "r")) !== FALSE) {
            $currentRecord = 1;
            while (($datos = fgetcsv($fileCsv, 1000, ",")) !== FALSE) {

                $conversion_name = $datos[0];
                $gclid = $datos[1]; //String
                $conversion_time = $datos[2];
                $conversion_value = $datos[3];
                
                $regLog = "\nregistro n° {$currentRecord} : \n"."\tconversion_name : {$conversion_name},\n\tgclid : {$gclid},\n\tconversion_time : {$conversion_time},\n\tconversion_value : {$conversion_value} \n\n";
                echo $regLog;
                
                //resolve google gclick
                $currentRecord++;

                if($return == true){
                    return self::main($conversion_name, $gclid, $conversion_time, $conversion_value);
                }else{
                    self::main($conversion_name, $gclid, $conversion_time, $conversion_value);
                }
            }
            fclose($fileCsv);
        }
    }



    /**
     * RAMIRO PORTAS
     * [populeCSV description] : Se encarga de cargar una fila a un archivo csv 
     * @param  [array] $data [description] Array 2D, 2D:Array(#lf) 
     * @return [void]       [description]
     * #lf : $conversion_name, $gclid, $conversion_time, $conversion_value)
     */
    public static function populeCSV($data, $mode = "w"){
        xbug("populeCSV");
        $lista = array();
        foreach ($data as $i => $v) {
            $lista[] = $v; //Array($conversion_name, $gclid, $conversion_time, $conversion_value)
        }
        $fp = fopen(self::getPathCsv(), $mode);
        foreach ($lista as $campos) {
            fputcsv($fp, $campos);
        }
        fclose($fp);
    }
}

//processCsv.sh
//$argv[1] : path CSV 
if(!empty($argv[1])){
    UploadOfflineConversions::setPathCsv($argv[1]);
    UploadOfflineConversions::processCsv(false);
}