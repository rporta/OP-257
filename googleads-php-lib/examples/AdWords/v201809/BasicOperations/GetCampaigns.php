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

namespace Google\AdsApi\Examples\AdWords\v201809\BasicOperations;

require __DIR__ . '/../../../../vendor/autoload.php';
//log
require_once '/var/www/OP-257/pixelNotification/dev1/utils/xbug.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\AdWords\v201809\cm\DateRange;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\Common\OAuth2TokenBuilder;


/**
 * This example gets all campaigns. To add a campaign, run AddCampaign.php.
 */
class GetCampaigns
{

    const PAGE_LIMIT = 500;

    public static function runExample(
        AdWordsServices $adWordsServices,
        AdWordsSession $session, 
        \stdClass $setSelector
    ) {

        $campaignService = $adWordsServices->get($session, CampaignService::class);

        // Create selector.
        $selector = new Selector();
        $selector->setFields($setSelector->setFields);
        $selector->setOrdering([new OrderBy($setSelector->setOrdering, SortOrder::ASCENDING)]);
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));
        $selector->setDateRange(new DateRange($setSelector->setDateRange[0], $setSelector->setDateRange[1]));

        $selector->setPredicates(
            [
                new Predicate('Status', PredicateOperator::NOT_IN, ['PAUSED'])
            ]
        );


        $totalNumEntries = 0;
        do {
            // Make the get request.
            $page = $campaignService->get($selector);

            // Display results.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $campaign) {
                    xbug("Id : {$campaign->getId()} | Name : {$campaign->getName()} | Status : {$campaign->getStatus()}");
                }
            }

            // Advance the paging index.
            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        printf("Number of results found: %d\n", $totalNumEntries);
    }

    public static function main($ClientCustomerId = "713-824-1599", $setSelector = null)
    {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->withClientCustomerId($ClientCustomerId)->build();

        if(empty($setSelector)){
            $setSelector = new \stdClass;
            $setSelector->setFields = ['Id', 'Name', 'Status'];
            $setSelector->setOrdering = 'Name';

        }else{    
            //si no se difinio un setOrdering, lo defino por defecto desde setFields
            if(empty($setSelector->setOrdering)){
                $setSelector->setOrdering = $setSelector->setFields[0];
            }
        }

        self::runExample(new AdWordsServices(), $session, $setSelector);
    }
}



// RAMIRO PORTAS 
// Los posibles campos que se pueden traer están declarados 
// en la siguiente guía, https://developers.google.com/adwords/api/docs/appendix/selectorfields#v201809-CampaignService
// estos campos se deben definir en el vector $setSelector->setFields (Obligatorio),
// si se desea ordenar la respuesta se debe modificar el campo $setSelector->setOrdering : String, asignando el valor de la columna
// si se desea filtar por fecha se debe definir en $setSelector->setDateRange, como Array, 
// index 0, fecha min 
// index 1, fecha max
// con el formato YYYYMMDD


$ClientCustomerId = "713-824-1599";

$setSelector = new \stdClass;
$setSelector->setFields = 
['AdServingOptimizationStatus',
'AdvertisingChannelSubType',
'AdvertisingChannelType',
'Amount',
'AppId',
'AppVendor',
'BaseCampaignId',
'BiddingStrategyGoalType',
'BiddingStrategyId',
'BiddingStrategyName',
'BiddingStrategyType',
'BudgetId',
'BudgetName',
'BudgetReferenceCount',
'BudgetStatus',
'CampaignGroupId',
'CampaignTrialType',
'DeliveryMethod',
'Eligible',
'EndDate',
'EnhancedCpcEnabled',
'FinalUrlSuffix',
'FrequencyCapMaxImpressions',
'Id',
'IsBudgetExplicitlyShared',
'Labels',
'Level',
'MaximizeConversionValueTargetRoas',
'Name',
'RejectionReasons',
'SelectiveOptimization',
'ServingStatus',
'Settings',
'StartDate',
'Status',
'TargetContentNetwork',
'TargetCpa',
'TargetCpaMaxCpcBidCeiling',
'TargetCpaMaxCpcBidFloor',
'TargetGoogleSearch',
'TargetPartnerSearchNetwork',
'TargetRoas',
'TargetRoasBidCeiling',
'TargetRoasBidFloor',
'TargetSearchNetwork',
'TargetSpendBidCeiling',
'TargetSpendSpendTarget',
'TimeUnit',
'TrackingUrlTemplate',
'UrlCustomParameters',
'VanityPharmaDisplayUrlMode',
'VanityPharmaText',
'ViewableCpmEnabled'];
$setSelector->setOrdering = 'Name';

//definimos la fecha ayer
$ayer = call_user_func(function() {
    $temp = new \DateTime();
    $temp->sub(new \DateInterval('P1D'));
    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
    return $temp;
});
//definimos la fecha hoy
$hoy = call_user_func(function() {
    $temp = new \DateTime();
    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
    return $temp;
});


//titulo por consola
call_user_func(function() {
    $hoy = new \DateTime();
    $ayer = new \DateTime();
    $ayer->sub(new \DateInterval('P1D'));
    xbug("Reporte de campañas activas, del {$ayer->format('Y-m-d')} al {$hoy->format('Y-m-d')}");
});

$setSelector->setDateRange = [$ayer, $hoy];

GetCampaigns::main($ClientCustomerId, $setSelector);


