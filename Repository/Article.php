<?php

namespace Repository;

use Exception;
use Framework\Model;
use \PDO;

class Article extends \Framework\Model
{

    public function getAllArticles($publish = null, $nbStart = null, $nbEnd = null): array
    {
        $sql = 'SELECT a.created_at as createdAt, a.user_id, a.content, a.title, a.excerpt, a.publish, a.image_filename, a.image_alt, u.username, c.name, a.user_id AS userId FROM articles AS a INNER JOIN articles_has_categories AS ac ON a.id = ac.articles_id INNER JOIN categories AS c ON ac.categories_id = c.id INNER JOIN users AS u ON a.user_id = u.id';

        if ($publish != null && $nbStart !== null or $nbEnd !== null) {
            $sql .= " WHERE publish =:publish ORDER BY ID DESC LIMIT " . $nbStart . "," . $nbEnd;

            $req = $this->executeRequest($sql, array(
                'publish' => $publish,
            ));

            return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
        } elseif ($publish != null) {
            $sql .= " WHERE publish =:publish ORDER BY ID DESC";
            $req = $this->executeRequest($sql, array(
                'publish' => $publish,
            ));
            return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
        }


        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Article::class);
    }

    public function save($article)
    {
        $sql = "INSERT INTO articles( created_at, content, title, publish, excerpt, image_filename, image_alt, user_id) VALUES( :created_at, :content, :title, :publish, :excerpt, :image_filename, :image_alt, :user_id) ";

        $req = $this->executeRequest($sql, array(
            'created_at' => $article->getCreatedAt(),
            'content' => $article->getContent(),
            'title' => $article->getTitle(),
            'publish' => $article->getPublish(),
            'excerpt' => $article->getExcerpt(),
            'image_filename' => $article->getExcerpt(),
            'image_alt' => $article->getExcerpt(),
            'user_id' => $article->getUserId(),
        ));
    }
}