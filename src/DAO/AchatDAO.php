<?php

namespace ChicAndCheap\DAO;

use ChicAndCheap\Domain\Achat;

class AchatDAO extends DAO
{
  
    protected function buildDomainObject($row) 
    {
        $achat = new achat();
        $achat->setIdVisiteur($row['id_visiteur']);
        $achat->setAchatDate($row['achat_date']);
        $achat->setAchatTotal($row['achat_total']);
        return $achat;
    }
 
    public function findByUser($id) 
    {
        $sql = "select * from Achat where id_visiteur=?";
        $result = $this->getDb()->fetchAll($sql, array($id));

		$achats = array();
        foreach ($result as $row) 
        {
            $achats[] = $this->buildDomainObject($row);
        }
        return $achats;
    }

	public function addAchat($id_visiteur, $total, $date)
	{
		$data = array(
			'id_visiteur' => $id_visiteur,
			'achat_date' => $date,
			'achat_total' => $total
			);
		$this->getDb()->insert('Achat', $data);
	}
    
        public function NbAchat($id_visiteur)
    {
    	$sql = "select count(*) AS Nb from achat where id_visiteur=?";
		$nombre = $this->getDb()->fetchAssoc($sql, array($id_visiteur));

        return $nombre['Nb'];
    }
    
}