<?php

namespace Repository;

use Framework\Model;
use Model\ArticleAsCategory;
use \PDO;

class ArticleHasCategory extends \Framework\Model
{

    public function getCategoriesHasArticle($articleId): array
    {
        $sql = 'SELECT articles_id AS articleId, categories_id AS categoriesId FROM articles_has_categories AS ac 
LEFT JOIN articles AS a ON ac.articles_id = a.id WHERE ac.articles_id =:id';

        $req = $this->executeRequest($sql, array(
            'id' => $articleId,
        ));
        return $req->fetchAll(PDO::FETCH_CLASS, ArticleAsCategory::class);
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

    public function deleteCategoryHasArticle($categoryId, $articleId)
    {

        foreach ($categoryId as $id){
            $sql = 'DELETE FROM articles_has_categories WHERE articles_id =:article_id AND categories_id=:categories_id';
            $req = $this->executeRequest($sql, array(
                'categories_id' => $id,
                'article_id' => $articleId
            ));
        }

    }

}