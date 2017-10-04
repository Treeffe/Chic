<?php

namespace ChicAndCheap\DAO;

use ChicAndCheap\Domain\Comment;

class CommentDAO extends DAO 
{
    /**
     * @var \ChicAndCheap\DAO\ArticleDAO
     */
    private $articleDAO;

    /**
     * @var \ChicAndCheap\DAO\VisiteurDAO
     */
    private $visiteurDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    public function setVisiteurDAO(VisiteurDAO $visiteurDAO) {
        $this->visiteurDAO = $visiteurDAO;
    }

    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAllByArticle($articleCode) {
        // The associated article is retrieved only once
        $article = $this->articleDAO->find($articleCode);

        // art_id is not selected by the SQL query
        // The article won't be retrieved during domain objet construction
        $sql = "select com_id, com_content, id_visiteur from t_comment where CODEART=? order by com_id";
        $result = $this->getDb()->fetchAll($sql, array($articleCode));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \ChicAndCheap\Domain\Comment
     */
    protected function buildDomainObject($row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('CODEART', $row)) {
            // Find and set the associated article
            $articleCode = $row['CODEART'];
            $article = $this->articleDAO->find($articleCode);
            $comment->setArticle($article);
        }
        if (array_key_exists('id_visiteur', $row)) {
            // Find and set the associated author
            $visiteurId = $row['id_visiteur'];
            $visiteur = $this->visiteurDAO->find($visiteurId);
            $comment->setAuthor($visiteur);
        }
        
        return $comment;
    }
        /**
     * Saves a comment into the database.
     *
     * @param \ChicAndCheap\Domain\Comment $comment The comment to save
     */
    public function save(Comment $comment) {
        $commentData = array(
            'CODEART' => $comment->getArticle()->getCode(),
            'id_visiteur' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
            );

        if ($comment->getId()) {
            // The comment has already been saved : update it
            $this->getDb()->update('t_comment', $commentData, array('com_id' => $comment->getId()));
        } else {
            // The comment has never been saved : insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comment and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
}
    
     /**

     * Returns a list of all comments, sorted by date (most recent first).

     *

     * @return array A list of all comments.

     */

    public function findAll() {

        $sql = "select * from t_comment order by com_id desc";

        $result = $this->getDb()->fetchAll($sql);


        // Convert query result to an array of domain objects

        $entities = array();

        foreach ($result as $row) {

            $id = $row['com_id'];

            $entities[$id] = $this->buildDomainObject($row);

        }

        return $entities;

    }
    
       /**

     * Removes all comments for an article

     *

     * @param $articleId The id of the article

     */

    public function deleteAllByArticle($articleId) {

        $this->getDb()->delete('t_comment', array('CODEART' => $articleCode));

    }
    

    /**
     * Returns a comment matching the supplied id.
     *
     * @param integer $id The comment id
     *
     * @return \ChicAndCheap\Domain\Comment|throws an exception if no matching comment is found
     */
    public function find($id) {
        $sql = "select * from t_comment where com_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No comment matching id " . $id);
    }

    // ...

    /**
     * Removes a comment from the database.
     *
     * @param @param integer $id The comment id
     */
    public function delete($id) {
        // Delete the comment
        $this->getDb()->delete('t_comment', array('com_id' => $id));
    }
       

    /**

     * Removes all comments for a visiteur

     *

     * @param integer $visiteurId The id of the visiteur

     */

    public function deleteAllByVisiteur($visiteurId) {

        $this->getDb()->delete('t_comment', array('id_visiteur' => $visiteurId));

    }
    



}