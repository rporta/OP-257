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
class GetCampaigns2
{

    public static function runExample(AdWordsSession $session, $filePath)
    {
        // Create selector.
        $selector = new Selector();
        $selector->setFields(
            [
                // "AccentColor",
                // "AccountDescriptiveName",
                // "AccountTimeZone",
                // "AdGroupId",
                // "AdGroupName",
                // "AdGroupStatus",
                // "AdStrengthInfo",
                // "AdType",
                // "AllowFlexibleColor",
                // "Automated",
                // "BaseAdGroupId",
                // "BaseCampaignId",
                // "BusinessName",
                // "CallOnlyPhoneNumber",
                // "CallToActionText",
                // "CampaignId",
                // "CampaignStatus",
                // "CombinedApprovalStatus",
                // "ConversionAdjustment",
                // "CreativeDestinationUrl",
                // "CreativeFinalAppUrls",
                // "CreativeFinalMobileUrls",
                // "CreativeFinalUrls",
                // "CreativeFinalUrlSuffix",
                // "CreativeTrackingUrlTemplate",
                // "CreativeUrlCustomParameters",
                // "CustomerDescriptiveName",
                // "Description",
                // "Description1",
                // "Description2",
                // "DevicePreference",
                // "DisplayUrl",
                // "EnhancedDisplayCreativeLandscapeLogoImageMediaId",
                // "EnhancedDisplayCreativeLogoImageMediaId",
                // "EnhancedDisplayCreativeMarketingImageMediaId",
                // "EnhancedDisplayCreativeMarketingImageSquareMediaId",
                // "ExpandedDynamicSearchCreativeDescription2",
                // "ExpandedTextAdDescription2",
                // "ExpandedTextAdHeadlinePart3",
                // "ExternalCustomerId",
                // "FormatSetting",
                // "GmailCreativeHeaderImageMediaId",
                // "GmailCreativeLogoImageMediaId",
                // "GmailCreativeMarketingImageMediaId",
                // "GmailTeaserBusinessName",
                // "GmailTeaserDescription",
                // "GmailTeaserHeadline",
                // "Headline",
                // "HeadlinePart1",
                // "HeadlinePart2",
                // "ImageAdUrl",
                // "ImageCreativeImageHeight",
                // "ImageCreativeImageWidth",
                // "ImageCreativeMimeType",
                // "ImageCreativeName",
                // "IsNegative",
                // "LabelIds",
                // "Labels",
                // "LongHeadline",
                // "MainColor",
                // "MarketingImageCallToActionText",
                // "MarketingImageCallToActionTextColor",
                // "MarketingImageDescription",
                // "MarketingImageHeadline",
                // "MultiAssetResponsiveDisplayAdAccentColor",
                // "MultiAssetResponsiveDisplayAdAllowFlexibleColor",
                // "MultiAssetResponsiveDisplayAdBusinessName",
                // "MultiAssetResponsiveDisplayAdCallToActionText",
                // "MultiAssetResponsiveDisplayAdDescriptions",
                // "MultiAssetResponsiveDisplayAdDynamicSettingsPricePrefix",
                // "MultiAssetResponsiveDisplayAdDynamicSettingsPromoText",
                // "MultiAssetResponsiveDisplayAdFormatSetting",
                // "MultiAssetResponsiveDisplayAdHeadlines",
                // "MultiAssetResponsiveDisplayAdLandscapeLogoImages",
                // "MultiAssetResponsiveDisplayAdLogoImages",
                // "MultiAssetResponsiveDisplayAdLongHeadline",
                // "MultiAssetResponsiveDisplayAdMainColor",
                // "MultiAssetResponsiveDisplayAdMarketingImages",
                // "MultiAssetResponsiveDisplayAdSquareMarketingImages",
                // "MultiAssetResponsiveDisplayAdYouTubeVideos",
                // "Path1",
                // "Path2",
                // "PolicySummary",
                // "PricePrefix",
                // "PromoText",
                // "ResponsiveSearchAdDescriptions",
                // "ResponsiveSearchAdHeadlines",
                // "ResponsiveSearchAdPath1",
                // "ResponsiveSearchAdPath2",
                // "ShortHeadline",
                // "Status",
                // "SystemManagedEntitySource",
                // "UniversalAppAdDescriptions",
                // "UniversalAppAdHeadlines",
                // "UniversalAppAdHtml5MediaBundles",
                // "UniversalAppAdImages",
                // "UniversalAppAdMandatoryAdText",
                // "UniversalAppAdYouTubeVideos",
                // "AdNetworkType1",
                // "AdNetworkType2",
                // "ClickType",
                // "ConversionAdjustmentLagBucket",
                // "ConversionCategoryName",
                // "ConversionLagBucket",
                // "ConversionTrackerId",
                // "ConversionTypeName",
                // "CriterionId",
                // "CriterionType",
                // "Date",
                // "DayOfWeek",
                // "Device",
                // "ExternalConversionSource",
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
                // "ActiveViewMeasurableImpressions",
                // "ActiveViewViewability",
                // "AllConversionRate",
                // "AllConversions",
                // "AllConversionValue",
                // "AverageCpc",
                // "AverageCpe",
                // "AverageCpm",
                // "AverageCpv",
                // "AveragePageviews",
                // "AveragePosition",
                // "AverageTimeOnSite",
                // "BounceRate",
                // "ClickAssistedConversions",
                // "ClickAssistedConversionsOverLastClickConversions",
                // "ClickAssistedConversionValue",
                // "ConversionRate",
                // "Conversions",
                // "ConversionValue",
                "AccountCurrencyCode",
                "Cost",
                // "ActiveViewMeasurableCost",
                // "AverageCost",
                // "CostPerAllConversion",
                // "CostPerConversion",
                // "CostPerCurrentModelAttributedConversion",
                "Clicks",
                "Id",
                "CampaignName",
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
                // "Impressions",
                // "InteractionRate",
                // "Interactions",
                // "InteractionTypes",
                // "PercentNewVisitors",
                // "TopImpressionPercentage",
                // "ValuePerAllConversion",
                // "ValuePerConversion",
                // "ValuePerCurrentModelAttributedConversion",
                // "VideoQuartile100Rate",
                // "VideoQuartile25Rate",
                // "VideoQuartile50Rate",
                // "VideoQuartile75Rate",
                // "VideoViewRate",
                // "VideoViews",
                // "ViewThroughConversions"                
            ]
        );

        // Use a predicate to filter out paused criteria (this is optional).
        $selector->setPredicates(
            [
                new Predicate('Status', PredicateOperator::NOT_IN, ['PAUSED'])
            ]
        );
        //fecha min, fecha max
        $selector->setDateRange(new DateRange("20190715", "20190716"));

        // Create report definition.
        $reportDefinition = new ReportDefinition();
        $reportDefinition->setSelector($selector);
        $reportDefinition->setReportName(
            'ad performance report report #' . uniqid()
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
            ReportDefinitionReportType::AD_PERFORMANCE_REPORT
        );
        $reportDefinition->setDownloadFormat(DownloadFormat::CSV);

        $reportDownloader = new ReportDownloader($session);

        $reportSettingsOverride = (new ReportSettingsBuilder())->includeZeroImpressions(false)->build();

        $reportDownloadResult = $reportDownloader->downloadReport(
            $reportDefinition,
            $reportSettingsOverride
        );


        $rta = $reportDownloadResult->getAsString();
        xbug($rta);

        // $reportDownloadResult->saveToFile($filePath);
        // printf(
            // "Report with name '%s' was downloaded to '%s'.\n",
            // $reportDefinition->getReportName(),
            // $filePath
        // );
    }

    // . $ClientCustomerId, corresponde al numero de cuenta,
    // . consultamos a la cuenta las campañas disponibles
    // . Opratel ARS, 713-824-1599
    public static function main($ClientCustomerId = "713-824-1599", $setSelector = null)
    {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // See: AdWordsSessionBuilder for setting a client customer ID that is
        // different from that specified in your adsapi_php.ini file.
        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->withClientCustomerId($ClientCustomerId)->build();

        $filePath = sprintf(
            '%s.csv',
            tempnam(sys_get_temp_dir(), 'ad-performance-report-')
        );
        self::runExample($session, $filePath);
    }
}

GetCampaigns2::main();
