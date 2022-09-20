<?php

namespace Repository;

use Exception;
use Framework\Model;
use \PDO;

class Article extends \Framework\Model
{

    public function getAllArticles($publish = null, $nbStart = null, $nbEnd = null): array
    {
        $sql = 'SELECT a.id, a.created_at as createdAt, a.updated_at as updatedAt, a.user_id as userId, a.content, a.title, a.excerpt, a.publish, a.image_filename as imageFilename, a.image_alt as imageAlt, ac.articles_id,  u.username, GROUP_CONCAT(c.name) as name
FROM articles_has_categories AS ac 
RIGHT JOIN articles AS a 
ON ac.articles_id = a.id 
LEFT JOIN categories AS c 
ON ac.categories_id = c.id 
LEFT JOIN users AS u 
        ON a.user_id = u.id';

        if ($publish != null && $nbStart !== null or $nbEnd !== null) {
            $sql .= " WHERE a.publish =:publish ORDER BY a.updated_at DESC LIMIT " . $nbStart . "," . $nbEnd;
            $req = $this->executeRequest($sql, array(
                'publish' => $publish,
            ));

            return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
        } elseif ($publish != null) {
            $sql .= " WHERE a.publish =:publish GROUP BY a.id ORDER BY a.updated_at DESC ";
            $req = $this->executeRequest($sql, array(
                'publish' => $publish,
            ));
            return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
        } elseif ($publish === null && $nbStart === null or $nbEnd === null){
            $sql .= " GROUP BY a.id ORDER BY a.updated_at DESC";
            $req = $this->executeRequest($sql);
            return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
        }
        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
    }

    /**
     * @throws Exception
     */
    public function getArticle($articleId)
    {
        $sql = 'SELECT a.id, a.created_at as createdAt, a.updated_at as updatedAt, a.user_id as userId, a.content, a.title, a.excerpt, a.publish, a.image_filename as imageFilename, a.image_alt as imageAlt, ac.articles_id,  u.username, GROUP_CONCAT(c.name) as name
FROM articles_has_categories AS ac 
RIGHT JOIN articles AS a 
ON ac.articles_id = a.id 
LEFT JOIN categories AS c 
ON ac.categories_id = c.id 
LEFT JOIN users AS u 
        ON a.user_id = u.id WHERE a.id=:id
        GROUP BY a.id';
        $article = $this->executeRequest($sql, array(
            'id' => $articleId,
        ));

        if ($article->rowCount() == 1) {
            $article->setFetchMode(PDO::FETCH_CLASS, \Model\Article::class);
            return $article->fetch();
        }
        else {
            throw new \Exception("Aucun article ne correspond à l'identifiant '$articleId'");
        }
    }

    public function getLastIdArticle()
    {
        $sql = 'SELECT id FROM articles ORDER BY id DESC LIMIT 1';
        $article = $this->executeRequest($sql);


        $article->setFetchMode(PDO::FETCH_CLASS, \Model\Article::class);
        return $article->fetch()->getId();

    }

    public function deleteArticle($articleId)
    {
        $sql = 'DELETE FROM articles WHERE id =:id';
        $req = $this->executeRequest($sql, array(
            'id' => $articleId,
        ));
    }

    public function save($article)
    {
        $sql = "INSERT INTO articles( created_at, content, title, publish, excerpt, image_filename, image_alt, user_id, updated_at) VALUES( :created_at, :content, :title, :publish, :excerpt, :image_filename, :image_alt, :user_id, :updated_at)";

        $req = $this->executeRequest($sql, array(
            'created_at' => $article->getCreatedAt(),
            'updated_at' => $article->getUpdatedAt(),
            'content' => $article->getContent(),
            'title' => $article->getTitle(),
            'publish' => $article->getPublish(),
            'excerpt' => $article->getExcerpt(),
            'image_filename' => $article->getImageFilename(),
            'image_alt' => $article->getImageAlt(),
            'user_id' => $article->getUserId(),
        ));
    }

    public function update($article)
    {
        $sql = "UPDATE articles SET updated_at=:updated_at, content=:content, title=:title, publish=:publish, excerpt=:excerpt, image_filename=:image_filename, image_alt=:image_alt, user_id=:user_id WHERE id=:id";

        $req = $this->executeRequest($sql, array(
            'id' => $article->getId(),
            'updated_at' => $article->getUpdatedAt(),
            'content' => $article->getContent(),
            'title' => $article->getTitle(),
            'publish' => $article->getPublish(),
            'excerpt' => $article->getExcerpt(),
            'image_filename' => $article->getImageFilename(),
            'image_alt' => $article->getImageAlt(),
            'user_id' => $article->getUserId(),
        ));

    }
}