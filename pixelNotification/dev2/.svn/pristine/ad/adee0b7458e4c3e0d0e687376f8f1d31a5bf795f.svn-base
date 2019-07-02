<?php
/**
 * Este fichero contiene la clase MoviPlus
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
 * MoviPlus
 *
 * @category Web_Service
 * @package  PixelNotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class MoviPlus
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
            $this->logger->write(__METHOD__ . ' Request to MoviPlus: Pixel: ' . $presubscription->Pixel, 'debug');
        }

        $response = file_get_contents(str_replace('{click_id}', $presubscription->Pixel, $this->config->get('adNetworks.79.url')));
        $return = false;

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit MoviPlus Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit MoviPlus Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $return = 'No response';
        } else {
            $parsedResponse = json_decode($response);
            if ($this->debug) {
                $this->logger->write('Hit MoviPlus Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $response, 'debug');
            }
            if (strtolower($parsedResponse->status) == 'ok'){
                $return = 'OK';
            }else{
                $return = 'ERROR';
            }
        }

        $this->result = $return;
    }
}