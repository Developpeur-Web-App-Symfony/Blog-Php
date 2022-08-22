<?php
namespace Controller;

use Exception;
use Repository\Category;

class Dashboard extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {
        $this->generateView([
        ]);
    }

    /**
     * @throws Exception
     */
    public function accountManagement()
    {
        $this->generateView([
        ]);
    }

    /**
     * @throws Exception
     */
    public function articleManagement()
    {
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $allCategory = $repositoryCategory->getAllCategory();

        $repositoryArticle = new \Repository\Article();
        $allArticle = $repositoryArticle->getAllArticles();


        $this->generateView([
            'allCategory' => $allCategory ?? null,
            'allArticle' => $allArticle ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function commentManagement()
    {
        $this->generateView([
        ]);
    }

    /**
     * @throws Exception
     */
    public function userManagement()
    {
        $this->generateView([
        ]);
    }
}