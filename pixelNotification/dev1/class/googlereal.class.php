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

//v201809
require_once "/var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/Remarketing/UploadOfflineConversions.php";

//log
require_once '/var/script/pixelNotification/dev1/utils/xbug/xbug.php';

//dirbase
require_once '/var/script/pixelNotification/dev1/utils/dirbase/folderFile.php';
require_once '/var/script/pixelNotification/dev1/utils/dirbase/dirBase.php';
require_once '/var/script/pixelNotification/dev1/utils/dirbase/file.php';
require_once '/var/script/pixelNotification/dev1/utils/dirbase/folder.php';

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
        $this->logger = logger::getInstance();
        $this->logger->write(__METHOD__ . ' $this->presubscription : ' . var_export($presubscription, true), 'debug');
        $this->config = configJson::getInstance();
        $this->debug = $this->config->get('debug');

        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Request to Googlereal: Pixel: ' . $presubscription->Pixel . ' | Pub: '. $presubscription->Pub, 'debug');
        }
        xbug($presubscription);

        //seteamos fecha ( Fecha | FechaProceso )
        $fecha = $presubscription->Fecha;

        //fix fecha : sacamos caracteres ":", "-"
        $fecha = call_user_func(function() use (&$fecha) {
            $out = str_replace([":", "-"], "", $fecha) . " America/Argentina/Buenos_Aires";
            return $out;
        });

        $data = Array();
        $reg = Array("apiflor", $presubscription->Pixel, $fecha, 0);
        $data[] = $reg;

        //aca se resuelve todo con la depentendica (v2019006)
        UploadOfflineConversions::setPathCsv($this->pathCsv);
        UploadOfflineConversions::populeCSV($data);

        //aca result : string puede ser "OK" | "soap error";
        $rta = UploadOfflineConversions::processCsv();

        if($rta !== "OK"){
            //rta es "soap error"
            $tempRta = explode(".", $rta);
            $this->result = $tempRta[1];
        }else{
            //rta es "OK"
            $this->result = $rta;
        }

        xbug($this->result);
        
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' $this->result : ' . $this->result, 'debug');
        }

        // 2- Si falla por el motivo que fuere, va a almacenar ese pixel en un archivo con fecha del dia, donde iremos acumulando todos los que fallen.
        if($this->result !== "OK" && !in_array($this->result, $this->config->get('adNetworks.87.non-retries'))){
            $path = getcwd();
            $dirbase = new dirBase("/var/script/pixelNotification/dev1/");

            $seachFileCSV = call_user_func(function(){
                $hoy = new DateTime();
                $limit = Array();
                $limit['am'][0] = new DateTime("{$hoy->format('Y-m-d')} 00:00:00");
                $limit['am'][1] = new DateTime("{$hoy->format('Y-m-d')} 11:59:59");
                $limit['pm'][0] = new DateTime("{$hoy->format('Y-m-d')} 12:00:00");
                $limit['pm'][1] = new DateTime("{$hoy->format('Y-m-d')} 23:59.59");
                $fileName = str_replace([":", "-"], "", $hoy->format("Y-m-d"));
                if($hoy->getTimestamp() >= $limit['am'][0]->getTimestamp() && $hoy->getTimestamp() <= $limit['am'][1]->getTimestamp()){
                    //estamos dentro del rango de 00:00:00 a 11.59:59,
                    $fileName = $fileName . "_1.csv";
                }else if($hoy->getTimestamp() >= $limit['pm'][0]->getTimestamp() && $hoy->getTimestamp() <= $limit['pm'][1]->getTimestamp()){
                    //estamos dentro del rango de 12:00:00 a 23:59.59,
                    $fileName = $fileName . "_2.csv";
                }
                return $fileName;
            });

            $bufferInfo = $dirbase->getObj("buffer")->getInfo();

            $existeSearchFileCSV = in_array($seachFileCSV, $bufferInfo['arrayFile']);

            if($existeSearchFileCSV){
                //metemos el registro en el CSV
                UploadOfflineConversions::setPathCsv("/var/script/pixelNotification/dev1/buffer/" . $seachFileCSV);
                // UploadOfflineConversions::populeCSV($data, "r+");
                UploadOfflineConversions::populeCSV($data, "a+");
            }else{
                //creamos y metemos el registro en el CSV
                $dirbase->getObj("buffer")->createFile($seachFileCSV);
                UploadOfflineConversions::setPathCsv("/var/script/pixelNotification/dev1/buffer/" . $seachFileCSV);
                // UploadOfflineConversions::populeCSV($data, "r+");
                UploadOfflineConversions::populeCSV($data, "a+");
            }
        }
    }
}
