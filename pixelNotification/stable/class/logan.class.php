<?php
/**
 * Este fichero contiene la clase Logan
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
 * Logan
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Logan
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
            $this->logger->write(__METHOD__ . ' Request to Logan: Pixel: ' . $presubscription->Pixel, 'debug');
            $this->logger->write(__METHOD__ . ' Request to url: ' . str_replace('{click_id}', $presubscription->Pixel, $this->config->get('adNetworks.70.urlnew')), 'debug');
        }

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, str_replace('{click_id}', $presubscription->Pixel, $this->config->get('adNetworks.70.urlnew')));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $result = curl_exec($ch);
        $resultObj = json_decode($result);
        $response = 'ERROR';

        // close cURL resource, and free up system resources
        curl_close($ch);

        if (empty($result)) {
            if ($this->debug) {
                $this->logger->write('Hit Logan Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Logan Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
        } else {
            if ((isset($resultObj->code) && $resultObj->code === 201) || (isset($resultObj->success) && $resultObj->success === true)) {
                if ($this->debug) {
                    $this->logger->write('Hit Logan Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
                }
                $response = 'OK';
            } else {
                if ($this->debug) {
                    $this->logger->write('Hit Logan Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response RAW Error: ' . $result, 'debug');
                }
                $this->logger->write('Hit Logan Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response RAW Error: ' . $result, 'error');
            }

        }

        $this->result = $response;
    }
}
