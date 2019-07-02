<?php
/**
 * Este fichero contiene la clase Globadlity
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
class Globadlity
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

        $_map = ['OK', 'ERROR']; // response 0 means ok, otherwise 1: there was an error
        $return = false;

        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Request to Globadlity: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $ch = curl_init();
        // set URL and other appropriate options -- {IDTRX}
        curl_setopt($ch, CURLOPT_URL, $this->config->get('adNetworks.51.url') . $presubscription->Pixel);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit Globadlity Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Globadlity Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $return = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response from Globadlity: ' . var_export($response, true), 'debug');
            }
            /* '<?xml version="1.0" encoding="utf-8"?><string xmlns="http://tempuri.org/">{"Response": [{"status":0,"message":"Success"}]}</string>' */
            $p = xml_parser_create();
            xml_parse_into_struct($p, $response, $aResponse);
            xml_parser_free($p);
            $parsedResponse = json_decode($aResponse[0]['value']);
            if (json_last_error() === JSON_ERROR_NONE) {
                $status = $parsedResponse->Response[0]->status;
                if (!isset($_map[$status])) {
                    $status = 1; // status out range in _map array = set to ERROR
                }
                if ($this->debug) {
                    $this->logger->write('Hit Globadlity Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $_map[$status], 'debug');
                }
                $return = $_map[$status];

            } else {
                if ($this->debug) {
                    $this->logger->write('Hit Globadlity Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: Error parsing response: ' . json_last_error_msg(), 'debug');
                }
                $this->logger->write('Hit Globadlity Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: Error parsing response: ' . json_last_error_msg(), 'error');
                $return = 'Response error';
            }
        }

        $this->result = $return;

    }

}