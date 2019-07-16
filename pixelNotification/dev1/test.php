<?php
$path = getcwd();

include_once "utils/xbug/xbug.php";
include_once "utils/dirbase/folderFile.php";
include_once "utils/dirbase/folder.php";
include_once "utils/dirbase/file.php";
include_once "utils/dirbase/dirBase.php";

//class google real
require_once "/var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/Remarketing/UploadOfflineConversions.php";

$logDate = new DateTime();
xbug("{$logDate->format('Y-m-d H:i:s')} : test.php");

use Google\AdsApi\Examples\AdWords\v201809\Remarketing\UploadOfflineConversions;

UploadOfflineConversions::setPathCsv("/var/script/pixelNotification/dev1/buffer/20190714_2.csv");
UploadOfflineConversions::processCsv(false);

//04 se creo porque ese dia se escucho 87, [20, 22], entro 1 de 22
//05 se creo porque ese dia se escucho 87, [20], entro 33 de 20



// apiconversion,CjwKCAjwvJvpBRAtEiwAjLuRPTnY-rsvUGWkXHDOkwCKH8z60tgvfQdSrvuOSP2vVcEfxKeBw72JExoCcMQQAvD_BwE,"20190706 143928 America/Argentina/Buenos_Aires",0
// apiconver,CjwKCAjwvJvpBRAtEiwAjLuRPflPsldXMwW9-SRDLecRDf8CdMQVeG5g9rycije_BkESz9dwRbuiDxoCTDwQAvD_BwE,"20190711 105207 America/Argentina/Buenos_Aires",0
