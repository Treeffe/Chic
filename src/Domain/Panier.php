<?php

namespace ChicAndCheap\Domain;

class Panier
{
    /**
     * Prod id.
     *
     * @var int
     */
    private $codeArt;

	/**
     * User id.
     *
     * @var int
     */
    private $id_visiteur;
    
    /**
     * Price.
     *
     * @var float
     */
    private $prix;



    public function getIdVisiteur()
	{
        return $this->visiteur_id;
    }

    public function setIdVisiteur($visiteur_id)
	{
        $this->visiteur_id = $visiteur_id;
    }
    
   public function getCodeArt() {
        return $this->codeArt;
    }
    public function setCodeArt($codeArt) {
        $this->codeArt = $codeArt;
    }
    
    public function getPrix()
	{
        return $this->prix;
    }

    public function setPrix($prix)
	{
        $this->prix = $prix;
    }
}