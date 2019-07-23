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
// default
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';
require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';

// xbug
require_once '/var/script/pixelNotification/dev1/utils/xbug/xbug.php';

// v201806
require_once "/var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/BasicOperations/GetCampaignsInfoOpratel.php";
// db oprafwk
require_once '/var/www/html/oprafwk/lib/db/db.class.php';

// resolve Select Insert Update on db
require_once '/var/script/googleAds/class/resolverSelectInsertUpdate.php';

use Google\AdsApi\Examples\AdWords\v201809\BasicOperations\GetCampaignsInfoOpratel;

// define logger
$logger = logger::getInstance();
$logger->setSessionId(uniqid());
$logger->setPath(__DIR__ .'/logs/');
$logger->setFileNamePrefix('getInfocampaigns');

// define config 
$config = configJson::getInstance();
$config->setConfigFile(__DIR__.'/config/config.json');

// define xbug, logger display console and append file 
$logDate = new DateTime();
$xbugConfig->fileLog = __DIR__ .'/logs/xbug.log';

// define db connection
$db = new db(
    $config->get('Db.sqlServer.dsn') . $config->get('Db.sqlServer.schema_center'),
    $config->get('Db.sqlServer.user'),
	$config->get('Db.sqlServer.pass')
);

// ahora se puede usar xbug
xbug("{$logDate->format('Y-m-d H:i:s')} : getGapiCampaignMap.php");

// $argv[1] : fecha de consuta en format YYYYMMDD, parametro opcional 
if(!empty($argv[1])){
    // se pasa la fecha por parametro (desde: $argv[1] - P1D, hasta: $argv[1])
	$desde = call_user_func(function() use (&$argv) {
	    $temp = new DateTime($argv[1]);
	    $temp->sub(new DateInterval('P1D'));
	    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
	    return $temp;
	});
	$hasta = call_user_func(function() use (&$argv) {
	    $temp = new DateTime($argv[1]);
	    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
	    return $temp;
	});

}else{
    // se define la fecha de consulta en el script, (desde: ayer, hasta: hoy)
	$desde = call_user_func(function() {
	    $temp = new DateTime();
	    $temp->sub(new DateInterval('P1D'));
	    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
	    return $temp;
	});
	$hasta = call_user_func(function() {
	    $temp = new DateTime();
	    $temp = str_replace([":", "-"], "", $temp->format('Y-m-d'));
	    return $temp;
	});
}

// ahora se puede consumir rta : Array
$ClientCustomerId = "713-824-1599";
$setSelector->setDateRange = [$desde, $hasta];// <- viende de GetCampaignsInfoOpratel.php
$rta = GetCampaignsInfoOpratel::main($ClientCustomerId, $setSelector);

// aca se debe resolver en OpratelConsulta.dbo.GAPI_CampaignMap 
$rtaResolver = new resolverSelectInsertUpdate($rta);
$rtaResolver->runResolver();