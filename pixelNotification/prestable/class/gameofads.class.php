<?php
/**
 * Este fichero contiene la clase GameOfAds
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
class GameOfAds
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
            $this->logger->write(__METHOD__ . ' Request to GameOfAds: Pixel: ' . $presubscription->Pixel . ' | Pub: '. $presubscription->Pub, 'debug');
        }
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, str_replace(['{click_id}', '{pub_id}'], [$presubscription->Pixel, $presubscription->Pub], $this->config->get('adNetworks.77.url')));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit GameOfAds Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit GameOfAds Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $response = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write('Hit GameOfAds Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $response, 'debug');
            }
        }

        $this->result = $response;

    }

}
