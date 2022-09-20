<?php
namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;
use Repository\Category;

class Dashboard extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {
        if (intval(Session::getSession()->getRoleLevel()) == Controller::VISITOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page, veuillez vous authentifier"]);
            header("Location: /home/index");
            exit();
        }
        $user = new \Model\User();
        $userRepository = new \Repository\User($user);
        $userBdd = $userRepository->getUser(Session::getSession()->getId());

        $this->generateView([
            'user' => $userBdd
        ]);
    }

    /**
     * @throws Exception
     */
    public function articleManagement()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
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

}