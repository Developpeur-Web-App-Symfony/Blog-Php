<?php

namespace Repository;

use Framework\Model;
use \PDO;

class Category extends \Framework\Model
{
    private object $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function getAllCategory(): array
    {
        $sql = 'SELECT id, name FROM categories';

        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\Category::class);
    }

    /**
     * @throws \Exception
     */
    public function getCategory($categoryId)
    {
        $sql = 'SELECT id, name FROM categories WHERE id=:id';
        $category = $this->executeRequest($sql, array(
            'id' => $categoryId,
        ));

        if ($category->rowCount() == 1) {
            $category->setFetchMode(PDO::FETCH_CLASS, \Model\Category::class);
            return $category->fetch();
        }
        else {
            throw new \Exception("Aucune catégorie ne correspond à l'identifiant '$categoryId'");
        }
    }

    public function deleteCategory($categoryId)
    {
        $sql = 'DELETE FROM categories WHERE id =:id';
        $req = $this->executeRequest($sql, array(
            'id' => $categoryId,
        ));
    }

    public function checkCategoryNameInBdd(): int
    {
        $sql = 'SELECT name FROM categories WHERE name=:name';
        $req = $this->executeRequest($sql, array(
            'name' => $this->category->getName()));

        return $req->rowCount();
    }


    public function updateCategory()
    {
        $sql = 'UPDATE categories SET name=:name WHERE id=:id';

        $req = $this->executeRequest($sql, array(
            'id' => $this->category->getId(),
            'name' => $this->category->getName(),
        ));
    }

    public function save()
    {
        $sql = "INSERT INTO categories(name) VALUES(:name)";

        $req = $this->executeRequest($sql, array(
            'name' => $this->category->getName(),
        ));
    }

}