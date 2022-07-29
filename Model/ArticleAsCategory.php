<?php
namespace Model;

class ArticleAsCategory extends \Framework\Model
{
    private mixed $articleId;
    private mixed $categoriesId;

    /**
     * @return mixed
     */
    public function getArticleId(): mixed
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId(mixed $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getCategoriesId(): mixed
    {
        return $this->categoriesId;
    }

    /**
     * @param mixed $categoriesId
     */
    public function setCategoriesId(mixed $categoriesId): void
    {
        $this->categoriesId = $categoriesId;
    }


}