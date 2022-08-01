<?php

namespace Repository;

use Framework\Model;
use \PDO;

class ArticleHasCategory extends \Framework\Model
{

//    private object $category;
//    private object $article;
//
//    public function __construct($category, $article)
//    {
//        $this->category = $category;
//        $this->article = $article;
//    }

    public function save($categoryId, $articleId)
    {
        $sql = "INSERT INTO articles_has_categories(articles_id, categories_id) VALUES(:article_id, :category_id)";

        $req = $this->executeRequest($sql, array(
            'article_id' => $articleId,
            'category_id' => $categoryId,
        ));
    }

}