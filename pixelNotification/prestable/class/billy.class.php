<?php
/**
 * Este fichero contiene la clase Billy
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
class Billy
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
            $this->logger->write(__METHOD__ . ' Request to Billy: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents($this->config->get('adNetworks.56.url') . $presubscription->Pixel);
        $return = false;

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit Billy Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Billy Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $return = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write(__METHOD__ . ' Response from Billy: ' . var_export($response, true), 'debug');
            }

            $decode = json_decode($response);
            if (json_last_error() === JSON_ERROR_NONE) {
                // response from billy was a valid json, that's all we care for. Response is always success, even if not.
                if ($this->debug) {
                    $this->logger->write('Hit Billy Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
                }
                $return = 'OK';

            } else {
                if ($this->debug) {
                    $this->logger->write('Hit Billy Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response:  Response with error: ' . json_last_error_msg(), 'debug');
                }
                $this->logger->write('Hit Billy Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response:  Response with error: ' . json_last_error_msg(), 'error');
                $return = 'Response error';
            }
        }
        $this->result = $return;
    }

}
