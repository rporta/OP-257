<?php
/**
 * Este fichero contiene la clase Mobusi
 * 
 * @category Web_Service
 * @package  SMT
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */

require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';
require_once '/var/script/pixelNotification/dev1/utils/xbug.php';
require_once "/var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/Remarketing/UploadOfflineConversions.php";

use Google\AdsApi\Examples\AdWords\v201809\Remarketing\UploadOfflineConversions;

/**
 * Servicios Portal WAP
 *
 * @category Web_Service
 * @package  SMT
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Googlereal
{
    public $result;



    /**
     * RAMIRO PORTAS
     * [$pathCsv description] : Este campo almacena el path del archivo CSV
     * @var string
     */
    public $pathCsv = "/var/script/pixelNotification/dev1/buffer/data.csv";
    
    /**
     * Constructor
     *
     * @param stdClass $presubscription Presubscription
     *
     * @return void
     */
    public function __construct($presubscription)
    {
        xbug(__METHOD__);
        xbug($presubscription);
        // $this->logger = logger::getInstance();
        // $this->config = configJson::getInstance();
        // $this->debug = $this->config->get('debug');

        // if ($this->debug) {
            // $this->logger->write(__METHOD__ . ' Request to Googlereal: Pixel: ' . $presubscription->Pixel . ' | Pub: '. $presubscription->Pub, 'debug');
        // }

		//$params = explode('@', $presubscription->Pixel);


  //       xbug($params);
		// if (count($params) < 3){
		// 	$this->result = 'Missing params';
		// 	return;
		// }

        //RAMIRO PORTAS :
        //
        // tracking/{key1}/$params[1]
        // campaign/{key2}/$params[2]
        // gclid/{click_id}/$params[0]
        // 
        // Santiago : 
        // 1. "API CONVERSION"  es el valor de CONVERSION_NAME_HERE
        // 2. TE PASO "GOOGLE_CLICK_ID"
        // 3. LA HORA LA TOMAS DE LOG PIXEL? VERIFICAR CON BENJA (<-sacar de mysql)
        // 4. 0 (<- esto corresponde al valor)
        // 

        //seteamos fecha ( Fecha | FechaProceso )
        $fecha = $presubscription->FechaProceso;

        //fix fecha : sacamos caracteres ":", "-"
        $fecha = call_user_func(function() use (&$fecha) {
            $out = str_replace([":", "-"], "", $fecha) . " America/Argentina/Buenos_Aires";
            return $out;
        });

        $data = Array();
        $reg = Array("apiconver", $presubscription->Pixel, $fecha, 0);
        $data[] = $reg;

        xbug($reg);
        //aca se resuelve todo con la depentendica (v2019006)
        UploadOfflineConversions::setPathCsv($this->pathCsv);
        UploadOfflineConversions::populeCSV($data);


        //aca result : string puede ser "OK" | "No response";

        $this->result = UploadOfflineConversions::processCsv();
        // xbug($this->result);
    }
}

// $presubscription = new stdClass();
// $presubscription->PresuscripcionId = "40720680";
// $presubscription->Origen = "56950586680";
// $presubscription->MedioId = "422";
// $presubscription->PaqueteId = "805";
// $presubscription->SponsorId = "13";
// $presubscription->Fecha = "2019-07-01 11:14:17";
// $presubscription->MedioSuscripcionId = "4009";
// $presubscription->ExternalId =" ";
// $presubscription->AdNetwork = "69";
// $presubscription->Pixel = "Cj0KCQjwgezoBRDNARIsAGzEfe4khMciMIe0GMcb8h9AlcWcgnw6Ct7lEZd6HoFdlcmQt6uzzQRIk_EaAp_uEALw_wcB";
// $presubscription->Pub = " ";
// $presubscription->Portal = "http://www.tulandia.net/landing/VWYbne/adwordscenam/Cj0KCQjw3uboBRDCARIsAO2XcYD27HxhfqL14HgGyAyEWTJR";
// $presubscription->FechaProceso = "2019-07-02 11:34:10";
// $presubscription->UserAgent = "Mozilla/5.0 (Linux; Android 8.0.0; TA-1028 Build/O00623) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36";
// $presubscription->SuscripcionId = "77361076";
// $presubscription->PorcentualNotificacion = "0";
// $presubscription->CPA = "1";

// $Googlereal = new Googlereal($presubscription);