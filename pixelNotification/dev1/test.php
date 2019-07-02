<?php
/**
 * Process pixel notifications
 *
 * @category Process
 * @package  AdNetworks
 * @author   Leonardo Nachman <leonardo.nachman@opratel.com>
 * @license  http://www.opratel.com Opratel
 * @link     http://www.opratel.com Opratel
 */
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';
require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';
require_once __DIR__ . '/class/notification.class.php';
require_once __DIR__ . '/class/tmclicks.class.php';


$logger = logger::getInstance();
$logger->setSessionId(uniqid());
$logger->setPath(__DIR__ .'/logs/');
$logger->setFileNamePrefix('process_pixel');

$config = configJson::getInstance();
$config->setConfigFile(__DIR__.'/config/config.json');

$presubscription = new stdClass;
$presubscription->Origen = '56912345677';
$presubscription->MedioId = 96;
$presubscription->PaqueteId = 748;
$presubscription->SponsorId = 13;
$presubscription->Fecha = '2018-07-24 15:15:15';
$presubscription->MedioSuscripcionId = '';
$presubscription->ExternalId = null;
$presubscription->AdNetwork = 78;
$presubscription->Pixel = 'test';
$presubscription->Pub = null;
$presubscription->Portal = 'http://test.test';
$presubscription->FechaProceso = '2018-07-24 15:15:15';
$presubscription->UserAgent = 'test';
$presubscription->PorcentualNotification = 0;
$presubscription->CPA = '1';

$class = new Tmclicks($presubscription);