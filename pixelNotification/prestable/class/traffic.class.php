<?php
/**
 * Este fichero contiene la clase Traffic
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
 * Servicios Portal WAP
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Traffic
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
            $this->logger->write(__METHOD__ . ' Request: Traffic: Pixel: ' . $presubscription->Pixel . ' | Pub: '. $presubscription->Pub, 'debug');
        }


        $response = @file_get_contents(str_replace(['{click_id}', '{pub_id}', '{payout}'], [$presubscription->Pixel, $presubscription->Pub, $presubscription->CPA], $this->config->get('adNetworks.80.url')));

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit Traffic Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Traffic Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $response = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write('Hit Traffic Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . trim($response), 'debug');
            }
        }

        $this->result = trim($response);

    }

}
