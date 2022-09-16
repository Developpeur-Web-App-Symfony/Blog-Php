<?php
namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;
use Services\ValidatorAddCategory;

class Category extends \Framework\Controller
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
    public function create()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        if ($this->request->existsParameter('saveCategory')) {
            if ($this->request->existsParameter('saveCategory') == 'save') {
                $category = new \Model\Category();
                $category->setName($this->request->getParameter('name'));
                $validator = new ValidatorAddCategory($category);
                if ($validator->formAddCategoryValidate()) {
                    if ($validator->categoryNameValidate())
                    {
                        $repositoryCategory = new \Repository\Category($category);
                        $repositoryCategory->save();
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Catégorie créer avec succès"]);
                        header('Location: /dashboard/articleManagement');
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Nom de catégorie déjà existant"]);
                        header('Location: /category/create');
                    }
                    exit();
                }
            }
        }
        $this->generateView([
            'validator' => $validator ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function update()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        $categoryId = $this->request->getParameter('id');
        $category = new \Model\Category();
        $repositoryCategory = new \Repository\Category($category);
        $categorySelect = $repositoryCategory->getCategory($categoryId);
        if ($this->request->existsParameter('saveCategory')) {
            if ($this->request->existsParameter('saveCategory') == 'save') {
                $category->setId($categoryId);
                $category->setName($this->request->getParameter('name'));
                $validator = new ValidatorAddCategory($category);

                if ($validator->formAddCategoryValidate()) {
                    if ($validator->categoryNameValidate())
                    {
                        $repositoryCategory->updateCategory();
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Catégorie modifier avec succès"]);
                        header('Location: /dashboard/articleManagement');
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Nom de catégorie déjà existant"]);
                        header("Location: /category/update/$categoryId");
                    }
                    exit();
                }
            }
        }

        $this->generateView([
            'category' => $categorySelect ?? null,
            'validator' => $validator ?? null,
        ]);
    }

    public function delete()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        $categoryId = $this->request->getParameter('id');
        $category = new \Model\Category();
        $repositoryCategory = new \Repository\Category($category);
        $repositoryCategory->deleteCategory($categoryId);
        $this->request->getSession()->setAttribut('flash', ['alert' => "Catégorie supprimer avec succès"]);
        header('Location: /dashboard/articleManagement');
        exit;
    }

}