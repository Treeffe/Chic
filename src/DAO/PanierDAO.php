<?php

namespace ChicAndCheap\DAO;

use ChicAndCheap\Domain\Panier;
use ChicAndCheap\Domain\Article;

class PanierDAO extends DAO
{
	
    protected function buildDomainObject($row) 
    {
        $articlePanier = new article();
        $articlePanier->setCode($row['CODEART']);
        $articlePanier->setLibelle($row['LIBELLEART']);
        $articlePanier->setDescription($row['DESCRIPTION']);
        $articlePanier->setPrix($row['Prix']);
        $articlePanier->setTypeArticle($row['CodeType']);
        return $articlePanier;
    }
	
	/**
     * Return liste des articles commandés par un utilisateur
     *
     * @return array liste des articles
     */
    public function findProductsByUser($id_visiteur) 
    {
        $sql = "select Article.* from article, Panier where article.codeArt = Panier.codeArt and Panier.id_visiteur = ?";
        $result = $this->getDb()->fetchAll($sql, array($id_visiteur));

        // Convert query result to an array of domain objects
        $articles = array();
        foreach ($result as $row)
        {
            $articleId = $row['CODEART'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        return $articles;
    }
    
	public function supprimerPanier($id_visiteur)
    {
    	$sql = "select * from Panier where id_visiteur = ?";
    	$result = $this->getDb()->fetchAll($sql, array($id_visiteur));
    	
    	$sql = "delete from Panier where id_visiteur = ?";
    	$this->getDb()->executeQuery($sql, array($id_visiteur));
    	foreach ($result as $row)
        {
        	
            $sql = "delete from Panier where codeArt = ?";
            $this->getDb()->executeQuery($sql, array($row['codeArt']));
            
            // supprime l'article 
			//$sql = "delete from article where codeArt = ?";
			//$this->getDb()->executeQuery($sql, array($row['codeArt']));      
		}
    }
    
	/**
     * Return total du prix du panier d'un utilisateur
     *
     * @param id_visiteur $id_visiteur Id de l'utilisateur
     */
    public function totalPanier($id_visiteur) 
    {
        $sql = "SELECT SUM(article.prix) AS 'total' FROM article, Panier WHERE Panier.id_visiteur=? and Panier.codeArt = article.codeArt";
        $prix = $this->getDb()->fetchAssoc($sql, array($id_visiteur));

        return $prix['total'];
    }
	
    
    
    public function articleExistant($id_visiteur, $codeArt) 
    {
        $sql = "SELECT * FROM Panier WHERE Panier.id_visiteur=? and Panier.codeArt = ?";
        $result = $this->getDb()->fetchAssoc($sql, array($id_visiteur, $codeArt));

        return ($result != NULL);
    }

	public function addProduct($id_visiteur, $codeArt)
	{
		$data = array(
			'id_visiteur' => $id_visiteur,
			'codeArt' => $codeArt
			);
		$this->getDb()->insert('Panier', $data);
	}
	

	public function removeProduct($id_visiteur, $codeArt)
	{
		$sql = "DELETE FROM Panier WHERE Panier.id_visiteur = ? AND Panier.codeArt = ?";
		$this->getDb()->executeQuery($sql, array($id_visiteur, $codeArt));
	}
}