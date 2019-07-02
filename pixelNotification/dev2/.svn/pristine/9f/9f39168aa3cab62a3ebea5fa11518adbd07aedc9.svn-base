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


$logger = logger::getInstance();
$logger->setSessionId(uniqid());
$logger->setPath(__DIR__ .'/logs/');
$logger->setFileNamePrefix('process_pixel');

$config = configJson::getInstance();
$config->setConfigFile(__DIR__.'/config/config.json');

$checkUltimoCobro = 0;
if (count($argv) == 2) {
    $_arg = filter_var($argv[1], FILTER_SANITIZE_NUMBER_INT);
    if (in_array($_arg, [0, 1])) {
        $checkUltimoCobro = $_arg;
    }
}

$runningInstances = `ps ax | grep '/usr/bin/php' | grep 'pixelNotificationProcess.php {$checkUltimoCobro}' | grep -v grep | grep -v Ss | wc -l`;

if ($runningInstances > $config->get('maxInstances')) {
    $logger->write('The limit of max simultaneous run has reached: ' . $runningInstances, 'info');
} else {
    $notificationObj = new Notification($checkUltimoCobro);
    $notificationObj->processNotifications();
}

