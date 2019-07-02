<?php
/**
 * Este fichero contiene la clase MOBrain
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
 * Spiroox
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class MOBrain
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
            $this->logger->write(__METHOD__ . ' Request to MOBrain: Pixel: ' . $presubscription->Pixel, 'debug');
        }
        $opts = [
            "http" => [
            "method" => "GET",
            "header" => "ACCEPT: text/application\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents(str_replace('{clickid_macro}', $presubscription->Pixel, $this->config->get('adNetworks.72.url')), false, $context);
        $return = false;

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'debug');
            }
            $this->logger->write(__METHOD__ . ' Response: '.$http_response_header[0], 'error');
            $return = $http_response_header[0];
        } else {
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response from MOBrain: ' . var_export($response, true), 'debug');
            }
            if ($response == "OK") {
                $return = 'OK';
            } else {
                $return = $response;
            }
            if ($this->debug) {
                 $this->logger->write('Hit MOBrain Msisdn: ' . $presubscription->Origen . ' | clickid_macro : ' . $presubscription->Pixel . ' | Response: ' . $return, 'debug');
            }
        }

        $this->result = $return;
    }
}