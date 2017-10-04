<?php

namespace ChicAndCheap\Domain;

class Achat
{
	/**
     * User id.
     *
     * @var int
     */
    private $id_visiteur;
    
    /**
     * achat date.
     *
     * @var date
     */
    private $date;
    
    /**
     * achat total.
     *
     * @var float
     */
    private $total;



    public function getIdVisiteur()
	{
        return $this->visiteur_id;
    }

    public function setIdVisiteur($visiteur_id)
	{
        $this->visiteur_id = $visiteur_id;
    }
    
    public function getAchatTotal()
	{
        return $this->total;
    }

    public function setAchatTotal($total)
	{
        $this->total = $total;
    }
    
    public function getAchatDate()
	{
        return $this->date;
    }

    public function setAchatDate($date)
	{
        $this->date = $date;
    }
}