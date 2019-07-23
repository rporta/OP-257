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

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201809\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinition;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDownloader;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\DateRange;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201809\cm\ReportDefinitionReportType;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * Downloads CRITERIA_PERFORMANCE_REPORT for the specified client customer ID.
 */
class GetCampaignsInfoCostOpratel
{

    public static function runExample(AdWordsSession $session, \stdClass $setSelector)
    {
        // Create selector.
        $selector = new Selector();
        $selector->setFields($setSelector->setFields);

        // Use a predicate to filter out paused criteria (this is optional).
        // $selector->setPredicates(
        //     [
        //         new Predicate('Status', PredicateOperator::NOT_IN, ['PAUSED'])
        //     ]
        // );
        //fecha min, fecha max


        //titulo por consola
        call_user_func(function() use ($setSelector){
            $ayer = new \DateTime($setSelector->setDateRange[0]);
            $hoy = new \DateTime($setSelector->setDateRange[1]);
            xbug("Reporte del costo de las campañas activas, del {$ayer->format('Y-m-d')} al {$hoy->format('Y-m-d')}");
        });

        $selector->setDateRange(new DateRange($setSelector->setDateRange[0], $setSelector->setDateRange[1]));

        // Create report definition.
        $reportDefinition = new ReportDefinition();
        $reportDefinition->setSelector($selector);
        $reportDefinition->setReportName(
            'campaign performance report #' . uniqid()
        );
        // ReportDefinitionDateRangeType:: 
        // TODAY
        // YESTERDAY
        // LAST_7_DAYS
        // LAST_WEEK
        // LAST_BUSINESS_WEEK
        // THIS_MONTH
        // LAST_MONTH
        // ALL_TIME
        // CUSTOM_DATE
        // LAST_14_DAYS
        // LAST_30_DAYS
        // THIS_WEEK_SUN_TODAY
        // THIS_WEEK_MON_TODAY
        // LAST_WEEK_SUN_SAT

        $reportDefinition->setDateRangeType(
            ReportDefinitionDateRangeType::CUSTOM_DATE
        );
        $reportDefinition->setReportType(
            ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT
        );
        $reportDefinition->setDownloadFormat(DownloadFormat::CSV);

        $reportDownloader = new ReportDownloader($session);

        $reportSettingsOverride = 
        (new ReportSettingsBuilder())->
        skipReportHeader(true)->
        skipColumnHeader(false)->
        skipReportSummary(true)->
        useRawEnumValues(true)->
        includeZeroImpressions(false)->
        build();

        $reportDownloadResult = $reportDownloader->downloadReport(
            $reportDefinition,
            $reportSettingsOverride
        );

        $tempRta = $reportDownloadResult->getAsString();

        //fix rta : array

        $rta = call_user_func(function() use ($tempRta) {
            $out = array();
            $temp = explode("\n", $tempRta);
            foreach ($temp as $k => $r) {
                if(!empty($r)){
                    if($k === 0){
                        $keys = explode(",", $r);
                    }else{
                        array_push($out, array_combine($keys, explode(",", $r)));
                    }
                }
            }
            return $out;
        });

        //fix totalizador : array 

        $totalizador = call_user_func(function() use ($rta) {
            $fieldsTotalizador = 
            [
              "Cost",
              "Clicks",  
              "Impressions",  
            ];
            $out = array_fill_keys($fieldsTotalizador, 0);
            foreach ($rta as $r) {
                foreach ($r as $k => $v) {
                    foreach ($out as $ok => &$ov) {
                        if($k === $ok){
                            $out[$ok] += $v;
                        }
                    }
                }
            }
            $out['Cost'] = $out['Cost'] / 1000000;
            $out['Cost'] = number_format ($out['Cost'], 2) ;
            return $out;
        });

        xbug($rta);
        xbug($totalizador);
        return $rta;
    }

    

    // . $ClientCustomerId, corresponde al numero de cuenta,
    // . consultamos a la cuenta las campañas disponibles
    // (Customer ID, Account Name)
    // 9829747784, GADS Admin:tipo administrador 
    // --638-046-8798, Uruguay I:tipo Google Ads
    // --324-194-8893, Bolivia I:tipo Google Ads
    // --650-074-0154, Cardinal_Opratel:tipo Google Ads
    // --812-435-4713, Costa Rica I:tipo Google Ads
    // --344-266-4368, Paraguay I:tipo Google Ads
    // --221-501-1770, Panama I:tipo Google Ads
    // --928-330-0290, Chile II:tipo Google Ads
    // --713-824-1599, Opratel ARS:tipo Google Ads
    // --922-340-3407, Opratel USD:tipo Google Ads

    public static function main($ClientCustomerId = "713-824-1599", $setSelector = null)
    {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // See: AdWordsSessionBuilder for setting a client customer ID that is
        // different from that specified in your adsapi_php.ini file.
        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->withClientCustomerId($ClientCustomerId)->build();

        if(empty($setSelector)){
            $setSelector = new \stdClass;

            $setSelector->setFields = [
                "CampaignId",
                "CampaignName",
                "CampaignStatus",
                "Clicks",
                "Cost",
                "Impressions"
            ];
            $setSelector->setOrdering = 'CampaignId';

        }else{    
            //si no se difinio un setOrdering, lo defino por defecto desde setFields
            if(empty($setSelector->setOrdering)){
                $setSelector->setOrdering = $setSelector->setFields[0];
            }
        }
        return self::runExample($session, $setSelector);
    }
}


$ClientCustomerId = "713-824-1599";

$setSelector = new \stdClass;
$setSelector->setFields = 
[                
    // "AccountCurrencyCode",
    // "AccountDescriptiveName",
    // "AccountTimeZone",
    // "AdvertisingChannelSubType",
    // "AdvertisingChannelType",
    // 
    // "Amount",
    // "BaseCampaignId",

    // "BiddingStrategyId",
    // "BiddingStrategyName",
    // "BiddingStrategyType",
    // "BudgetId",
    // "CampaignDesktopBidModifier",
    // 
    // "CampaignGroupId",
    "CampaignId",

    // "CampaignMobileBidModifier",
    // 
    "CampaignName",
    "CampaignStatus",

    // "CampaignTabletBidModifier",
    // "CampaignTrialType",
    // "ConversionAdjustment",
    // 
    // "CustomerDescriptiveName",

    // "EndDate",
    // "EnhancedCpcEnabled",
    // "ExternalCustomerId",
    // "FinalUrlSuffix",
    // "HasRecommendedBudget",
    // "IsBudgetExplicitlyShared",
    // "LabelIds",
    // "Labels",
    // "MaximizeConversionValueTargetRoas",
    // "Period",
    // "RecommendedBudgetAmount",
    // "ServingStatus",
    // "StartDate",
    // "TotalAmount",
    // "TrackingUrlTemplate",
    // "UrlCustomParameters",
    // "AdNetworkType1",
    // "AdNetworkType2",
    // "ClickType",
    // "ConversionAdjustmentLagBucket",
    // "ConversionAttributionEventType",
    // "ConversionCategoryName",
    // "ConversionLagBucket",
    // "ConversionTrackerId",
    // "ConversionTypeName",
    // "Date",
    // "DayOfWeek",
    // "Device",
    // "ExternalConversionSource",
    // "HourOfDay",
    // "Month",
    // "MonthOfYear",
    // "Quarter",
    // "Slot",
    // "Week",
    // "Year",
    // "AbsoluteTopImpressionPercentage",
    // "ActiveViewCpm",
    // "ActiveViewCtr",
    // "ActiveViewImpressions",
    // "ActiveViewMeasurability",
    // "ActiveViewMeasurableCost",
    // "ActiveViewMeasurableImpressions",
    // "ActiveViewViewability",
    // 
    // "AllConversionRate",
    // "AllConversions",
    // "AllConversionValue",

    // "AverageCost",
    // "AverageCpc",
    // "AverageCpe",
    // "AverageCpm",
    // "AverageCpv",
    // "AverageFrequency",
    // "AveragePageviews",
    // "AveragePosition",
    // "AverageTimeOnSite",
    // "BounceRate",
    // "ClickAssistedConversions",
    // "ClickAssistedConversionsOverLastClickConversions",
    // "ClickAssistedConversionValue",
    // 
    "Clicks",
    // "ContentBudgetLostImpressionShare",
    // "ContentImpressionShare",
    // "ContentRankLostImpressionShare",
    // "ConversionRate",
    // "Conversions",
    // "ConversionValue",
    "Cost",
    // "CostPerAllConversion",
    // "CostPerConversion",
    // "CostPerCurrentModelAttributedConversion",
    // "CrossDeviceConversions",
    // "Ctr",
    // "CurrentModelAttributedConversions",
    // "CurrentModelAttributedConversionValue",

    // "EngagementRate",
    // "Engagements",
    // "GmailForwards",
    // "GmailSaves",
    // "GmailSecondaryClicks",
    // "ImpressionAssistedConversions",
    // "ImpressionAssistedConversionsOverLastClickConversions",
    // "ImpressionAssistedConversionValue",
    // "ImpressionReach",
    // 
    "Impressions",

    // "InteractionRate",
    // "Interactions",
    // "InteractionTypes",
    // "InvalidClickRate",
    // "InvalidClicks",
    // 
    // "NumOfflineImpressions",
    // "NumOfflineInteractions",
    // "OfflineInteractionRate",
    // "PercentNewVisitors",
    // "RelativeCtr",

    // "SearchAbsoluteTopImpressionShare",
    // "SearchBudgetLostAbsoluteTopImpressionShare",
    // "SearchBudgetLostImpressionShare",
    // "SearchBudgetLostTopImpressionShare",
    // "SearchClickShare",
    // "SearchExactMatchImpressionShare",
    // "SearchImpressionShare",
    // "SearchRankLostAbsoluteTopImpressionShare",
    // "SearchRankLostImpressionShare",
    // "SearchRankLostTopImpressionShare",
    // "SearchTopImpressionShare",
    // "TopImpressionPercentage",
    // 
    // "ValuePerAllConversion",
    // "ValuePerConversion",
    // "ValuePerCurrentModelAttributedConversion",

    // "VideoQuartile100Rate",
    // "VideoQuartile25Rate",
    // "VideoQuartile50Rate",
    // "VideoQuartile75Rate",
    // "VideoViewRate",
    // "VideoViews",
    // "ViewThroughConversions",
];
$setSelector->setOrdering = 'CampaignId';

//definimos la fecha ayer
$ayer = call_user_func(function() {
    $temp = new \DateTime();
    $temp->sub(new \DateInterval('P1D'));
    $temp = $temp->format('Ymd');
    return $temp;
});
//definimos la fecha hoy
$hoy = call_user_func(function() {
    $temp = new \DateTime();
    $temp = $temp->format('Ymd');
    return $temp;
});

$setSelector->setDateRange = [$ayer, $hoy];


GetCampaignsInfoCostOpratel::main($ClientCustomerId, $setSelector);
