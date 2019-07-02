<?php
/**
 * Este fichero contiene la clase AlfaConvert
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
 * AlfaConvert
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class AlfaConvert
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
            $this->logger->write(__METHOD__ . ' Request to AlfaConvert: Pixel: ' . $presubscription->Pixel, 'debug');
        }
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $this->config->get('adNetworks.65.url') . $presubscription->Pixel);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $result = curl_exec($ch);
        $response = 'ERROR';

        // close cURL resource, and free up system resources
        curl_close($ch);

        if ($result === false) {
            if ($this->debug) {
                $this->logger->write('Hit AlfaConvert Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit AlfaConvert Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
        } else {
            if (strpos($result, 'Tracked') !== false || strpos($result, 'received') !== false) {
                $response = 'OK';
            } else {
                if ($this->debug) {
                    $this->logger->write('Hit AlfaConvert Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response RAW Error: ' . var_export($result, true), 'debug');
                }
                $this->logger->write('Hit AlfaConvert Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response RAW Error: ' . var_export($result, true), 'error');
            }

            if ($this->debug) {
                $this->logger->write('Hit AlfaConvert Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $response, 'debug');
            }
        }

        $this->result = $response;

    }

}
