<?php

namespace Repository;

use Exception;
use Framework\Model;
use \PDO;

class Category extends \Framework\Model
{

    public function getAllCategory(): array
    {
        /** Revoir la requete, commencer enpartant de la table articles **/
        $sql = 'SELECT id, name FROM categories';

        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Category::class);
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