<?php
/**
 * Este fichero contiene la clase Rekket
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
class Rekket
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
            $this->logger->write(__METHOD__ . ' Request to Rekket: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents(str_replace('{subid}', $presubscription->Pixel, $this->config->get('adNetworks.73.url')));
        $return = false;

        // if ($response === false) {
        //     $this->logger->write('Response: '.$http_response_header[0], 'error');
        //     $return = $http_response_header[0];
        // } else
        if (strpos($http_response_header[0], "200") !== false) {
            if ($this->debug) {
                $this->logger->write('Hit Rekket Msisdn: ' . $presubscription->Origen . ' | Pixel: ' . $presubscription->Pixel . ' | Response: '.$http_response_header[0], 'debug');
            }
            $return = "OK";
        } else {
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'debug');
            }
            $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'error');
            $return = $http_response_header[0];
        }

        $this->result = $return;
    }
}