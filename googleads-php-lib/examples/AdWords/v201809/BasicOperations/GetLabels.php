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

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\LabelService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * This example gets all labels in the account.
 */
class GetLabels
{

    const PAGE_LIMIT = 500;

    public static function runExample(
        AdWordsServices $adWordsServices,
        AdWordsSession $session
    ) {
        $labelService = $adWordsServices->get($session, LabelService::class);

        // Create selector.
        $selector = new Selector();
        $selector->setFields(['LabelId', 'LabelName']);
        $selector->setOrdering([new OrderBy('LabelName', 'ASCENDING')]);
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        $totalNumEntries = 0;
        do {
            // Make the get request.
            $page = $labelService->get($selector);

            // Print out information about labels obtained from LabelService.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $label) {
                    printf(
                        "Label with ID %d and name '%s' was found.\n",
                        $label->getId(),
                        $label->getName()
                    );
                }
            }

            // Advance the paging index.
            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        printf("Number of results found: %d\n", $totalNumEntries);
    }

    public static function main($ClientCustomerId = "713-824-1599")
    {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->withClientCustomerId($ClientCustomerId)->build();
        self::runExample(new AdWordsServices(), $session);
    }
}

GetLabels::main();
