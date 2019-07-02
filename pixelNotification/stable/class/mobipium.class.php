<?php
/**
 * Este fichero contiene la clase Mobipium
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */

require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';

/**
 * Rekket
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Mobipium
{
    public $result;

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
        $this->config = configJson::getInstance();
        $this->debug = $this->config->get('debug');

        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Request to Mobipium: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents(str_replace('{aff_sub}', $presubscription->Pixel, $this->config->get('adNetworks.75.url')));
        $return = false;

        if ($response === false) {
            $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'error');
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'debug');
                $this->logger->write('Hit Mobipium Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: no response', 'debug');
            }
            $return = $http_response_header[0];
        } elseif (strpos($http_response_header[0], "200") !== false && strtolower($response) == "ok") {
            if ($this->debug) {
                $this->logger->write('Hit Mobipium Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
            }
            $return = "OK";
        } else {
            if ($this->debug) {
                $this->logger->write('Hit Mobipium Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: '.$response, 'debug');
                $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0]." ".$response, 'debug');
            }
            $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0]." ".$response, 'error');
            $return = $http_response_header[0]. ($response != '' ? $response : "ERROR");
        }

        $this->result = $return;
    }
}