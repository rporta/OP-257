<?php
/**
 * Este fichero contiene la clase Tmclicks
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
class Tmclicks
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
        $return = false;

        // solamente loguea OK, no notifica
        if (in_array($presubscription->SponsorId, $this->config->get('adNetworks.78.skipSponsors'))) {
            if ($this->debug) {
                $this->logger->write('Hit Tmclicks Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
            }
            $return = 'OK';

        } else {
            $date = date('Y-m-d H:i:s');
            // $url = str_replace(['{click_id}', '{datetime}', '{user_identifier}'], [$presubscription->Pixel, urlencode($date), $presubscription->Origen], $this->config->get('adNetworks.78.url'));
            $url = str_replace(['{click_id}', '{datetime}', '{user_identifier}'], [$presubscription->Pixel, $date, $presubscription->Origen], $this->config->get('adNetworks.78.url'));
            if ($this->debug) {
                $this->logger->write('Url: ' . $url, 'debug');
            }
            $http_response_header = null;
            $response = @file_get_contents($url);
            $code = $this->getHttpCode($http_response_header);

            if ($code === 0) {
                $this->logger->write('Hit Tmclicks Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: No response', 'error');
                $return = 'No response';
            } else {
                if ($this->debug) {
                    $this->logger->write('Hit Tmclicks Response Code: '. $code .' | Header: ' . json_encode($http_response_header), 'debug');
                }
                $return = ($code == '202') ? 'OK' : 'ERROR';
            }
            if ($this->debug) {
                $this->logger->write('Hit Tmclicks Msisdn: ' . $presubscription->Origen . ' | Pixel: ' . $presubscription->Pixel . ' | Response: ' . $return, 'debug');
            }
        }

        $this->result = $return;

    }

    /**
     * [getHttpCode description]
     *
     * @param mixed $http_response_header [description]
     *
     * @return integer
     */
    protected function getHttpCode($http_response_header)
    {
        if (is_array($http_response_header)) {
            $parts=explode(' ', $http_response_header[0]);
            if (count($parts)>1) {
                //HTTP/1.0 <code> <text>
                return intval($parts[1]); //Get code
            }
        }
        return 0;
    }
}
