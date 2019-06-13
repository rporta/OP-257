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

namespace Google\AdsApi\Examples\AdManager\v201811\ProposalService;

require __DIR__ . '/../../../../vendor/autoload.php';

use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\v201811\BillingCap;
use Google\AdsApi\AdManager\v201811\BillingSource;
use Google\AdsApi\AdManager\v201811\Money;
use Google\AdsApi\AdManager\v201811\Proposal;
use Google\AdsApi\AdManager\v201811\ProposalCompanyAssociation;
use Google\AdsApi\AdManager\v201811\ProposalCompanyAssociationType;
use Google\AdsApi\AdManager\v201811\SalespersonSplit;
use Google\AdsApi\AdManager\v201811\ServiceFactory;
use Google\AdsApi\Common\OAuth2TokenBuilder;

/**
 * Creates proposals.
 *
 * This example is meant to be run from a command line (not as a webpage) and
 * requires that you've setup an `adsapi_php.ini` file in your home directory
 * with your API credentials and settings. See `README.md` for more info.
 */
class CreateProposals
{

    // Set the advertiser, salespersons, and trafficker IDs for creating the
    // proposal.
    const ADVERTISER_ID = 'INSERT_ADVERTISER_ID_HERE';
    const PRIMARY_SALESPERSON_ID = 'INSERT_PRIMARY_SALESPERSON_ID_HERE';
    const SECONDARY_SALESPERSON_ID = 'INSERT_SECONDARY_SALESPERSON_ID_HERE';
    const PRIMARY_TRAFFICKER_ID = 'INSERT_PRIMARY_TRAFFICKER_ID_HERE';

    public static function runExample(
        ServiceFactory $serviceFactory,
        AdManagerSession $session,
        $advertiserId,
        $primarySalespersonId,
        $secondarySalespersonId,
        $primaryTraffickerId
    ) {
        $proposalService = $serviceFactory->createProposalService($session);
        $networkService = $serviceFactory->createNetworkService($session);

        // Create a proposal.
        $proposal = new Proposal();
        $proposal->setName('Proposal #' . uniqid());

        // Create a proposal company association.
        $proposalCompanyAssociation = new ProposalCompanyAssociation();
        $proposalCompanyAssociation->setCompanyId($advertiserId);
        $proposalCompanyAssociation->setType(
            ProposalCompanyAssociationType::ADVERTISER
        );
        $proposal->setAdvertiser($proposalCompanyAssociation);

        // Create salesperson splits for the primary salesperson and secondary
        // salespeople.
        $primarySalesperson = new SalespersonSplit();
        $primarySalesperson->setUserId($primarySalespersonId);
        $primarySalesperson->setSplit(75000);
        $proposal->setPrimarySalesperson($primarySalesperson);

        $secondarySalesperson = new SalespersonSplit();
        $secondarySalesperson->setUserId($secondarySalespersonId);
        $secondarySalesperson->setSplit(25000);
        $proposal->setSecondarySalespeople([$secondarySalesperson]);

        // Set the probability to close to 100%.
        $proposal->setProbabilityOfClose(100000);

        // Set the primary trafficker on the proposal for when it becomes an
        // order.
        $proposal->setPrimaryTraffickerId($primaryTraffickerId);

        // Create a budget for the proposal worth 100 in the network local
        // currency.
        $budget = new Money();
        $budget->setMicroAmount(100000000);
        $budget->setCurrencyCode(
            $networkService->getCurrentNetwork()
                ->getCurrencyCode()
        );
        $proposal->setBudget($budget);

        $proposal->setBillingCap(BillingCap::CAPPED_CUMULATIVE);
        $proposal->setBillingSource(BillingSource::DFP_VOLUME);

        // Create the proposals on the server.
        $proposals = $proposalService->createProposals([$proposal]);

        // Print out some information for each created proposal.
        foreach ($proposals as $i => $proposal) {
            printf(
                "%d) Proposal with ID %d and name '%s' was created.%s",
                $i,
                $proposal->getId(),
                $proposal->getName(),
                PHP_EOL
            );
        }
    }

    public static function main()
    {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()
            ->build();

        // Construct an API session configured from an `adsapi_php.ini` file
        // and the OAuth2 credentials above.
        $session = (new AdManagerSessionBuilder())->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        self::runExample(
            new ServiceFactory(),
            $session,
            intval(self::ADVERTISER_ID),
            intval(self::PRIMARY_SALESPERSON_ID),
            intval(self::SECONDARY_SALESPERSON_ID),
            intval(self::PRIMARY_TRAFFICKER_ID)
        );
    }
}

CreateProposals::main();
