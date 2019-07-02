<?php
/**
 * Este fichero contiene la clase PML
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Adrian Claret <adrian.claret@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */

require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';

/**
 * Pml
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Adrian Claret <adrian.claret@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Pml
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
            $this->logger->write(__METHOD__ . ' Request to PML: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents(str_replace('{click_id}', $presubscription->Pixel, $this->config->get('adNetworks.83.url')));
        $return = false;

        if (strpos('failed', $response) === false) {
            if ($this->debug) {
                $this->logger->write('Hit PML Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit PML Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $return = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write('Hit PML Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $response, 'debug');
            }

            if (strtolower($response) == 'success=true'){
                $return = 'OK';
            }else{
                $return = 'ERROR';
            }
        }

        $this->result = $return;
    }
}
