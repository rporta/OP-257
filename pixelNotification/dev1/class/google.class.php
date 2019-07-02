<?php
/**
 * Este fichero contiene la clase Mobusi
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
class Google
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
            $this->logger->write(__METHOD__ . ' Request to Google: Pixel: ' . $presubscription->Pixel . ' | Pub: '. $presubscription->Pub, 'debug');
        }
        $ch = curl_init();

		$params = explode('@', $presubscription->Pixel);

		if (count($params) < 3){
			$this->result = 'Missing params';
			return;
		}

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, str_replace(['{key1}', '{key2}', '{click_id}'], [$params[1], $params[2], $params[0]], $this->config->get('adNetworks.69.url')));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        if ($response === false) {
            if ($this->debug) {
                $this->logger->write('Hit Google Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'debug');
            }
            $this->logger->write('Hit Google Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
            $response = 'No response';
        } else {
            if ($this->debug) {
                $this->logger->write('Hit Google Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: ' . $response, 'debug');
            }
        }

        xbug("result : {$response}");
        $this->result = $response;

    }
}
