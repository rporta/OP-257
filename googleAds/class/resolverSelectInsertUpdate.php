<?php 

// dependencias:prod
// require_once '/var/script/googleAds/class/batman.php';

// dependencias:local
require_once __DIR__.'/batman.php';


/**
 * Ramiro Portas
 * Esta clase esta destinada a resolver Insert o Update, de acuerdo a la data que recibe
 */

class resolverSelectInsertUpdate extends batman
{
	protected $db;
	protected $data;
	protected $destine;
	protected $logger;
	protected $case;
	protected $statusResolver;

	function __construct($data, $case)
	{	
		xbug(__METHOD__);
		$this->user_b(1); //batman
		
		global $db; 
		global $logger;

		$this->db = $db;
		$this->data = $data;
		$this->destine = $case;
		$this->logger = $logger;
		$this->case = $case;
		$this->statusResolver = false;
	}

	public function runResolver(){
		xbug(__METHOD__);
		$this->resolveCase();
	}

	public function resolveCase(){
		xbug(__METHOD__);
		switch ($this->case) {
			case 'OpratelConsulta.dbo.GAPI_CampaignMap':
				foreach ($this->data as $d) {
					if($this->queryCampingById($d)){
						$this->queryUpdateCamping($d);
					}else{
						$this->queryInsertCamping($d);
					}
				}
				$this->statusResolver = true;
				break;
			default:

				break;
		}
	}

	public function queryCampingById($data){
		xbug(__METHOD__);

		$query = 
		"SELECT \n".
		"\t CampaignMapId, \n".	
		"\t CampaignId, \n".
		"\t CampaignName, \n".
		"\t MedioSuscripcionId, \n".
		"\t Status, \n".
		"\t date_created, \n".
		"\t date_modified \n".
		"FROM {$this->destine} ".
		"WHERE 1 = 1 \n".
		"AND CampaignId = {$data['id']} \n";

		// xbug($query);
		$prep = $this->db->prepare($query);

		$rta = array();
		try {
			if ($this->db->execute()) {
                $rta = $this->db->fetch(PDO::FETCH_OBJ);
                xbug($rta);
            }
		} catch (Exception $e) {
			xbug($e);
			xbug($e->getMessage());
			$this->logger->write(__METHOD__.' -> ' . $e->getMessage(), 'info');
		}

		return sizeof($rta) == 0 ? false : true;
	}

	public function queryUpdateCamping($data){
		xbug(__METHOD__);
		$this->mapStatus($data['status']);

		$date_modified = $this->getDate();

        $aBindings = [
        	':CampaignId' => $data['id'],
            ':CampaignName' => $data['name'],
            ':Status' => $data['status'],
            ':date_modified' => $date_modified
        ];

		$query = 
		"UPDATE {$this->destine} \n".
		"SET CampaignName = :CampaignName, Status = :Status, date_modified = :date_modified \n".
		"WHERE 1 = 1 \n".
		"AND CampaignId = :CampaignId \n";

		$prep = $this->db->prepare($query);

		try {
			$rta = $this->db->executeWithBindings($aBindings);
		} catch (Exception $e) {
			xbug($e);
			xbug($e->getMessage());
			$this->logger->write(__METHOD__.' -> ' . $e->getMessage(), 'info');
		}

	}

	public function queryInsertCamping($data){
		xbug(__METHOD__);
		$this->mapStatus($data['status']);
		$date_created = $date_modified = $this->getDate();

        $aBindings = [
            ':CampaignId' => $data['id'],
            ':CampaignName' => $data['name'],
            ':MedioSuscripcionId' => 0,
            ':Status' => $data['status'],
            ':date_created' => $date_created,
            ':date_modified' => $date_modified
        ];

		$query = 
		"INSERT INTO {$this->destine} (CampaignId, CampaignName, MedioSuscripcionId, Status, date_created, date_modified)\n".
		"VALUES (:CampaignId, :CampaignName, :MedioSuscripcionId, :Status, :date_created, :date_modified)";

		$prep = $this->db->prepare($query);

		try {
			$rta = $this->db->executeWithBindings($aBindings);
		} catch (Exception $e) {
			xbug($e);
			xbug($e->getMessage());
			$this->logger->write(__METHOD__.' -> ' . $e->getMessage(), 'info');
		}
	}

	public function mapStatus(&$status){
		xbug(__METHOD__);
		switch ($status) {
			case 'REMOVED':
				$code = 0;
				break;
			case 'ENABLED':
				$code = 1;
				break;
			case 'PAUSED':
				$code = 2;
				break;
			case 'UNKNOWN':
				$code = 3;
				break;
		}
		$status = $code;
	}
	public function getDate(){
		xbug(__METHOD__);
		return (new DateTime())->format('Y-m-d H:i:s');
	}
}