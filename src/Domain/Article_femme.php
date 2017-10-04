<?php



namespace ChicANDCheap\Domain;


class Categorie

{
    private $id;
    private $libelle;
    
     /* Accesseur mutateur de l'attribut $id */
    
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    } 
    
    /* Accesseur mutateur de l'attribut $libelle */
    
    public function getLibelle(){
        return $this->libelle;
    }    
    public function setLibelle($libelle){
        $this->libelle = $libelle;
    }
}