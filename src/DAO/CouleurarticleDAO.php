<?php

namespace ChicAndCheap\DAO;

use ChicAndCheap\Domain\Couleurarticle;

class CouleurarticleDAO extends DAO
{
    /**
     * Renvoie la liste de tous les types, triées par libellé
     *
     * @return array La liste de tous les types
     */
    public function findAll() {
        $sql = "select * from couleur order by LIBELLECOULEUR";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convertit les résultats de requête en tableau d'objets du domaine
        $types = array();
        foreach ($result as $row) {
            $typeId = $row['CODECOULEUR'];
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
        $sql = "select * from couleur where CODECOULEUR=?";
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
        $couleur = new Couleurarticle();
        $couleur->setId($row['CODECOULEUR']);
        $couleur->setLibelle($row['LIBELLECOULEUR']);
        return $couleur;
    }
}