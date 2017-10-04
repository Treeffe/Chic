<?php
namespace ChicAndCheap\Domain;

class Article
{
    /**
     * Identifier.
     *
     * @var integer
     */
    private $Code;
    /**
     * Last name (nom de famille).
     *
     * @var string
     */
    private $libelle;
    
    private $taille;
    
    private $prix;
    
    private $description;
    
    private $typeArticle;
    
    private $couleurArticle;
    
    public function getCode() {
        return $this->code;
    }
    public function setCode($code) {
        $this->code = $code;
    }
    public function getLibelle() {
        return $this->libelle;
    }
    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
   
    public function getTaille() {
        return $this->taille;
    }
    public function setTaille($taille) {
        $this->taille = $taille;
    
    }
    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    
     
    public function getTypeArticle() {
        return $this->typeArticle;
    }

    public function setTypeArticle($typeArticle) {
        $this->typeArticle = $typeArticle;
    }
    
    public function getCouleurArticle() {
        return $this->couleurArticle;
    }

    public function setCouleurArticle($couleurArticle) {
        $this->couleurArticle = $couleurArticle;
    }
}