<?php

namespace ChicAndCheap\DAO;



use ChicAndCheap\Domain\Article;

class Article_femmeDAO extends DAO
{
   
    public function findAll() {
        $sql = "select * from Article where codecat = 'F' order by libelleArt";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convertit les résultats de requête en tableau d'objets du domaine
        $articles = array();
        foreach ($result as $row) {
            $articleLib = $row['LIBELLEART'];
            $articles[$articleLib] = $this->buildDomainObject($row);
        }
        return $articles;
    }

    /**
     * Renvoie une categorie à partir de son identifiant
     *
     * @param integer $id L'identifiant de la categorie
     *
     * @return \GSB\Domaine\Categorie|Lève un exception si aucune categorie ne correspond
     */
    public function find($libelleArt) {
        $sql = "select * from article where libelleArt=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Aucun article trouvé" . $libelleArt);
    }

    /**
     * Crée un objet Categorie à partir d'une ligne de résultat BD
     *
     * @param array $row La ligne de résultat BD
     *
     * @return \GSB\Domaine\Categorie
     */
    protected function buildDomainObject($row) {
        $article = new Article();
        $article->setCode($row['CODEART']);
        $article->setLibelle($row['LIBELLEART']);
        $article->setTaille($row['TAILLE']);
        $article->setPrix($row['Prix']);
        $article->setDescription($row['DESCRIPTION']);
   
        return $article;
    }
}