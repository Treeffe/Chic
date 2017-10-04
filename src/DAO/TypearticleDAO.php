<?php

namespace ChicAndCheap\DAO;

use ChicAndCheap\Domain\Typearticle;

class TypeArticleDAO extends DAO
{
    /**
     * Renvoie la liste de tous les types, triées par libellé
     *
     * @return array La liste de tous les types
     */
    public function findAllTypeArticle() {
        $sql = "select * from typearticle order by LibelleType";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convertit les résultats de requête en tableau d'objets du domaine
        $types = array();
        foreach ($result as $row) {
            $typeId = $row['CodeType'];
            $types[$typeId] = $this->buildDomainObject($row);
        }
        return $types;
    }

    /**
     * Renvoie un type à partir de son identifiant
     *
     * @param integer $id L'identifiant du type
     *
     * @return \GSB\Domain\Type|Lève une exception si aucun Type  ne correspond
     */
    public function find($id) {
        $sql = "select * from typearticle where CodeType=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Aucun type ne correspond à l'identifiant " . $id);
    }

    /**
     * Crée un objet Type à partir d'une ligne de résultat BD
     *
     * @param array $row La ligne de résultat BD
     *
     * @return \GSB\Domain\Type
     */
    protected function buildDomainObject($row) {
        $type = new Typearticle();
        $type->setId($row['CodeType']);
        $type->setLibelle($row['LibelleType']);
        return $type;
    }
}