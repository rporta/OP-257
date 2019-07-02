<?php
/**
 * Este fichero contiene la clase Kimia
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
 * Kimia
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Kimia
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
            $this->logger->write(__METHOD__ . ' Request to Kimia: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents($this->config->get('adNetworks.67.url') . $presubscription->Pixel);
        $return = false;

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit Kimia Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Kimia Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $return = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write('Hit Kimia Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
            }
            $return = 'OK';
        }

        $this->result = $return;
    }
}
