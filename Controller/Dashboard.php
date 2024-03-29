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
            $this->redirect("/home/index");
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
            $this->redirect("/home/index");
        }
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $allCategory = $repositoryCategory->getAllCategory();

        $repositoryArticle = new \Repository\Article();
        $allArticle = $repositoryArticle->getAllArticles();

        $token = Session::getSession()->getToken();

        $this->generateView([
            'allCategory' => $allCategory ?? null,
            'allArticle' => $allArticle ?? null,
            'token' => $token,
        ]);
    }

}