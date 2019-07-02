<?php
/**
 * Class Notification
 *
 * @category Process
 * @package  AdNetworks
 * @author   Leonardo Nachman <leonardo.nachman@opratel.com>
 * @license  http://www.opratel.com Opratel
 * @link     http://www.opratel.com Opratel
 */
require_once '/var/www/html/oprafwk/lib/config/configJson.class.php';
require_once '/var/www/html/oprafwk/lib/logger/logger.class.php';
require_once '/var/www/html/oprafwk/lib/db/db.class.php';
/**
 * Class Notification
 *
 * @category Process
 * @package  AdNetworks
 * @author   Leonardo Nachman <leonardo.nachman@opratel.com>
 * @license  http://www.opratel.com Opratel
 * @link     http://www.opratel.com Opratel
 */
class Notification
{
    private $_instanceName;
    private $_checkUltimoCobro;

    /**
     * [__construct description]
     *
     * @param boolean $checkUltimoCobro Traer usuarios con cobro de hoy, o no
     *
     * @return void
     */
    public function __construct($checkUltimoCobro)
    {
        $this->logger = logger::getInstance();
        $this->config = configJson::getInstance();

        $this->db = new db(
            $this->config->get('Db.sqlServer.dsn') . $this->config->get('Db.sqlServer.schema_center'),
            $this->config->get('Db.sqlServer.user'),
            $this->config->get('Db.sqlServer.pass')
        );

        $this->debug = $this->config->get('debug');

        $this->_checkUltimoCobro = $checkUltimoCobro;
        $_instances = $this->config->get('instanceName');
        $this->_instanceName = (isset($_instances[$checkUltimoCobro])) ? $_instances[$checkUltimoCobro] : $_instances[0];
        $this->logger->setTitle(strtoupper($this->_instanceName));

        $this->_sendHeartBeat();
    }

    /**
     * [processNotifications description]
     *
     * @return void
     */
    public function processNotifications()
    {
        $presubscriptions = $this->getPresubscriptions($this->_checkUltimoCobro);
        $this->logger->write('Pooling presubscriptions. Rows pooled: ' . count($presubscriptions), 'info');
        if (count($presubscriptions)) {
            // sort por país para corte de control de log
            $sponsorSort = [];
            $fechaProcSort = [];
            foreach ($presubscriptions as $key => $presubscription) {
                $sponsorSort[$key] = $presubscription->SponsorId;
                $fechaProcSort[$key] = $presubscription->FechaProceso;
            }
            array_multisort($sponsorSort, SORT_ASC, $fechaProcSort, SORT_ASC, $presubscriptions);
            $lastSponsor = null;
            foreach ($presubscriptions as $presubscription) {
                if ($lastSponsor != $presubscription->SponsorId) {
                    $lastSponsor = $presubscription->SponsorId;
                    $this->_changeLogName($presubscription->SponsorId);
                }
                $logText = ucfirst($this->config->get('adNetworks.' . $presubscription->AdNetwork . '.network'));
                $logText .= ' | Msisdn: ' . $presubscription->Origen  . ' | PresuscId.: ' . $presubscription->PresuscripcionId;
                $logText .= ' | PaqueteId: ' . $presubscription->PaqueteId . ' | MedioId: ' . $presubscription->MedioId;
                $logText .= ' | Pixel: ' . $presubscription->Pixel;
                if ($presubscription->Pub) {
                    $logText .=  ' | Pub: ' . $presubscription->Pub;
                }
                $this->logger->write($logText, 'info');

                $this->deletePresubscription($presubscription->PresuscripcionId);

                $sendPixel = $this->sendPixelClick($presubscription);
                if ($sendPixel && $sendPixel->result !== false) {
                    $this->savePixel($presubscription, $sendPixel->result);
                }
            }

        }
        $this->_changeLogName();
        $this->logger->write('Finishing process.', 'info');

    }

    /**
     * sendPixelClick
     *
     * @param object $presubscription Presuscripcion data
     *
     * @return mixed result or false
     */
    protected function sendPixelClick($presubscription)
    {
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Starting.', 'debug');
        }

        $config = $this->config->get('adNetworks.' . $presubscription->AdNetwork);
        if (!$config) {
            $this->logger->write(__METHOD__ . ' Config for AdNetwork ' . $presubscription->AdNetwork . 'not set', 'error');
            return false;
        }

        include_once  __DIR__ . '/'. $config['network']. '.class.php';
        if (!class_exists(ucfirst($config['network']))) {
            $this->logger->write(__METHOD__ . ' Class ' . ucfirst($config['network']) . 'missing', 'error');
            return false;
        }

        $class = ucfirst($config['network']);
        $process = new $class($presubscription->Origen, $presubscription->Pixel, $presubscription->Pub);

        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Ending.', 'debug');
        }
        return $process;
    }

    /**
     * getPresubscriptions
     *
     * @param boolean $checkUltimoCobro Chequear o no último cobro
     *
     * @return mixed - object or false
     */
    public function getPresubscriptions($checkUltimoCobro)
    {
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Starting.', 'debug');
        }
        $resultSet = [];

         $sql = <<<EOQ
            EXEC OpratelInfo.dbo.sp_Select_Presuscripcion_Usuarios_Suscriptos @SponsorsList = :sponsorsList, @UltimoCobro = :ultimoCobro
EOQ;

        $sponsorsUltimoCobro = $this->config->get('checkUltimoCobro');
        $sponsorsUltimoCobro = array_flip($sponsorsUltimoCobro); // revertimos key y valor. necesitamos los valores para usar como key en el xml
        $xml = new SimpleXMLElement('<ids/>');
        foreach ($sponsorsUltimoCobro as $key => $dummy) {
            $sponsorsUltimoCobro[$key] = 'id'; // seteamos 'id' como valor de todas las key (que son realmente los valores de sponsor)
        }
        array_walk($sponsorsUltimoCobro, array($xml, 'addChild'));

        $this->db->prepare($sql);
        $aBindings = [
            ':sponsorsList'   => $xml->asXML(),
            ':ultimoCobro' => $checkUltimoCobro
        ];

        try {
            // $result = $this->db->executeWithBindings($aBindings);

            // if ($result) {
            //     $resultSet = $this->db->fetch(PDO::FETCH_OBJ);
            // }
            $objeto = new stdClass;
            $objeto->PresuscripcionId = 4651237;
            $objeto->Origen = '50612345678';
            $objeto->MedioId = 445;
            $objeto->PaqueteId = 593;
            $objeto->SponsorId = 37;
            $objeto->Fecha = '2016-10-19 14:01:01.880';
            $objeto->MedioSuscripcionId = 950;
            $objeto->ExternalId = null;
            $objeto->AdNetwork = 48;
            $objeto->Pixel = 'pruebapixel';
            $objeto->Pub = 'pruebapub';
            $objeto->Portal = 'http://qa.tulandia.net/direct/BrtWFB/mobusi/pruebapixel/pruebapub';
            $objeto->FechaProceso = '2016-10-19 14:01:01.880';

            $resultSet[] = $objeto;
            $objeto = new stdClass;
            $objeto->PresuscripcionId = 4651310;
            $objeto->Origen = '50612345678';
            $objeto->MedioId = 263;
            $objeto->PaqueteId = 659;
            $objeto->SponsorId = 37;
            $objeto->Fecha = '2016-10-19 14:04:51.000';
            $objeto->MedioSuscripcionId = 1542;
            $objeto->ExternalId = null;
            $objeto->AdNetwork = 51;
            $objeto->Pixel = 'pruebapixelsinpub';
            $objeto->Pub = null;
            $objeto->Portal = 'http://www.tulandia.net/direct/7yXykA/globadlity/pruebapixelsinpub';
            $objeto->FechaProceso = '2016-10-19 14:04:51.000';

            $resultSet[] = $objeto;
        } catch (Exception $e) {
            $logger->write(__METHOD__ . "EXCEPTION. query: " . $e->getMessage(), 'error');
        }
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Ending.', 'debug');
        }
        return $resultSet;
    }

    /**
     * [savePixel description]
     *
     * @param stdClass $presubscription Presuscripcion Object
     * @param string   $result          Result
     *
     * @return void
     */
    public function savePixel($presubscription, $result)
    {
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Starting.', 'debug');
        }
        $exec = false;
        try {
            $query = <<<EOQ
                EXEC OpratelConsulta.dbo.sp_Insert_PixelLog
                    @Origen = :origen
                    ,@SponsorId = :sponsorId
                    ,@Portal = :portal
                    ,@MedioId = :medioId
                    ,@Pixel = :pixel
                    ,@Adservice = :adservice
                    ,@Action = :action
                    ,@Result = :result
                    ,@MedioSuscripcionId = :medioSuscripcionId
                    ,@PaqueteId = :paqueteId
                    ,@Pub = :pub

EOQ;
            $aBindings = [
                ':origen' => $presubscription->Origen,
                ':sponsorId' => $presubscription->SponsorId,
                ':portal' => $presubscription->Portal,
                ':medioId' => $presubscription->MedioId,
                ':pixel' => $presubscription->Pixel,
                ':adservice' => $presubscription->AdNetwork,
                ':action' => 'sendPixel',
                ':result' => $result,
                ':medioSuscripcionId' => $presubscription->MedioSuscripcionId,
                ':paqueteId' => $presubscription->PaqueteId,
                ':pub' => $presubscription->Pub
            ];

            $this->db->prepare($query);
            $execution = $this->db->executeWithBindings($aBindings);
            if ($execution) {
                $this->logger->write('Pixel Log saved for Msisdn:' . $presubscription->Origen . ' | MedioId: ' . $presubscription->MedioId . ' | PaqueteId: ' .$presubscription->PaqueteId, 'info');
            } else {
                $this->logger->write(__METHOD__ . ' Error while executing savePixel query to DB', 'error');
            }
        } catch (\Exception $e) {
            $this->logger->write(__METHOD__ . ' Error inserting query '. $query .' into SQL: ' . $e->getMessage(), 'error');
            $this->logger->write(__METHOD__ . ' Data: ' . json_encode($aBindings), 'error');
        }
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Ending.', 'debug');
        }
        return $execution;
    }

    /**
     * [deletePresubscription description]
     *
     * @param integer $presubscriptionId PresuscripcionId
     *
     * @return void
     */
    public function deletePresubscription($presubscriptionId)
    {
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Starting.', 'debug');
        }
        // we have a record to return. we delete it from DB
        $query = <<<EOQ
            DELETE FROM OpratelInfo.dbo.Presuscripcion
                WHERE PresuscripcionId = :presuscripcionId
EOQ;
        $prep =$this->db->prepare($query);
        $execution = $this->db->executeWithBindings([':presuscripcionId' => $presubscriptionId]);
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Ending.', 'debug');
        }
    }

    /**
     * _sendHeartBeat
     *
     * @return void
     */
    private function _sendHeartBeat()
    {
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Starting.', 'debug');
        }

        $query = <<<EOQ
            EXEC OpratelCenter.dbo.sp_heartbeats @Service = :service
EOQ;
        $prep =$this->db->prepare($query);
        $execution = $this->db->executeWithBindings([':service' => 'PixelNotification ' . $this->_instanceName]);
        if ($this->debug) {
            $this->logger->write(__METHOD__ . ' Ending.', 'debug');
        }
    }

    /**
     * [_changeLogName description]
     *
     * @param integer $sponsorId Sponsor Id-opcional
     *
     * @return void
     */
    private function _changeLogName($sponsorId = null)
    {
        $this->logger->setFileNamePrefix('process_pixel');
        if ($sponsorId) {
            $sponsorCode = $this->config->get('sponsor.' . $sponsorId);
            if ($sponsorCode) {
                $this->logger->setFileNamePrefix($sponsorCode, true);
            }
        }
    }

}