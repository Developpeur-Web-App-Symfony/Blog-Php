<?php

namespace Repository;

use Framework\Model;
use \PDO;

class ArticleHasCategory extends \Framework\Model
{

    public function getCategoryHasArticle(): array
    {
        $sql = 'SELECT * FROM articles_has_categories';

        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\ArticleAsCategory::class);
    }

    public function save($categoryId, $articleId)
    {

        foreach ($categoryId as $id)
        {
            $sql = "INSERT INTO articles_has_categories(articles_id, categories_id) VALUES(:article_id, :category_id)";

            $req = $this->executeRequest($sql, array(
                'article_id' => $articleId,
                'category_id' => $id,
            ));
        }


    }

}