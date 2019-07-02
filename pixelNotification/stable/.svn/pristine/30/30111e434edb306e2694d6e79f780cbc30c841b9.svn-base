<?php
/**
 * Este fichero contiene la clase Admobilly
 * 
 * @category Web_Service
 * @package  Pixelnotification
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
 * @package  Pixelnotification
 * @author   Opratel <info@opratel.com>
 * @license  [url] [description]
 * @version  1
 * @link     http://www.opratel.com
 */
class Admobilly
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
        if (in_array($presubscription->SponsorId, $this->config->get('adNetworks.81.skipSponsors'))) {
            if ($this->debug) {
                $this->logger->write('Hit Admobilly Msisdn: ' . $presubscription->Origen . ' | Pixel : ' . $presubscription->Pixel . ' | Response: OK', 'debug');
            }
            $return = 'OK';

        }

        $this->result = $return;

    }


}
