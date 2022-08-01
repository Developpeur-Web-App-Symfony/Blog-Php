<?php
namespace Controller;

use Exception;
use Repository\ArticleHasCategory;
use Repository\Category;
use Services\ValidatorAddArticle;
use Services\ValidatorAddCategory;
use Services\ValidatorUpload;

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
    public function createArticle()
    {
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $allCategory =$repositoryCategory->getAllCategory();

        if ($this->request->existsParameter('saveArticle')) {
            if ($this->request->existsParameter('saveArticle') == 'save') {
                $article = new \Model\Article();
                $userid = $this->request->getSession()->getSession()->getId();
                $article->setUserId($userid);
                $article->setTitle($this->request->getParameter('title'));
                $category->setId($this->request->getParameter('category'));
                $article->setContent($this->request->getParameter('content'));
                $article->setExcerpt($this->request->getParameter('excerpt'));
                $article->setImageFilename($this->request->getParameter('file')['name']);
                $article->setImageAlt($this->request->getParameter('alt'));
                $validator = new ValidatorAddArticle($article, $category);
                $validatorUpload = new ValidatorUpload($article);
                if ($validator->formAddArticleValidate()) {
                    if ($this->request->getParameter('publishArticle')) {
                        $article->setPublish(\Framework\Controller::PUBLISH['PUBLISH']);
                    } else{
                        $article->setPublish(\Framework\Controller::PUBLISH['DRAFT']);
                    }
                    $repositoryArticle = new \Repository\Article();
                    if ($validator->checkImagePresent()) {

                        if ($validator->checkImageUpload()) {
                            if ($validatorUpload->checkErrorFile()) {
                                if ($validatorUpload->formUploadValidate()) {
                                    $validatorUpload->uploadFile();
                                    $repositoryArticle->save($article);
                                    $this->request->getSession()->setAttribut('flash', ['alert' => "Article créer avec succès"]);
                                    header('Location: /dashboard/articleManagement');
                                    exit();
                                }
                            }
                        }
                    } else{
                        $article->setImageFilename(\Framework\Controller::IMAGE_DEFAULT['NAME']);
                        $article->setImageAlt(\Framework\Controller::IMAGE_DEFAULT['ALT']);
                        var_dump($article);die();
                        $repositoryArticle->save($article);
                        //                    ENREGISTREMENT DE LA CATEGORIE EN BDD
                        $lastArticle = $repositoryArticle->getLastIdArticle();
                        $article->setId($lastArticle);
                        $repositoryArticleHasCategory = new ArticleHasCategory();
                        $categoryId = $category->getId();
                        $articleId = $article->getId();
                        var_dump($categoryId);

                        $repositoryArticleHasCategory->save($categoryId, $articleId);

                        $this->request->getSession()->setAttribut('flash', ['alert' => "Article créer avec succès"]);
                        header('Location: /dashboard/articleManagement');
                        exit();
                    }

                }
            }
        }

        $this->generateView([
            'allCategory' => $allCategory,
            'validator' => $validator ?? null,
            'validatorUpload' => $validatorUpload ?? null,
            'article' => $article ?? null
        ]);
    }

    public function deleteArticle()
    {
        $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $article = new \Model\Article();
        $repositoryArticle = new \Repository\Article();
        $repositoryArticle->deleteArticle($articleId);
        $this->request->getSession()->setAttribut('flash', ['alert' => "Article supprimer avec succès"]);
        header('Location: /dashboard/articleManagement');
        exit;

    }

    /**
     * @throws Exception
     */
    public function createCategory()
    {
        if ($this->request->existsParameter('saveCategory')) {
            if ($this->request->existsParameter('saveCategory') == 'save') {
                $category = new \Model\Category();
                $category->setName($this->request->getParameter('name'));
                $validator = new ValidatorAddCategory($category);
                if ($validator->formAddCategoryValidate()) {
                    if ($validator->categoryNameValidate())
                    {
                        $repositoryCategory = new Category($category);
                        $repositoryCategory->save();
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Catégorie créer avec succès"]);
                        header('Location: /dashboard/articleManagement');
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Nom de catégorie déjà existant"]);
                        header('Location: /dashboard/createCategory');
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
    public function updateCategory()
    {
        $categoryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
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
                        header("Location: /dashboard/updateCategory$categoryId");
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

    public function deleteCategory()
    {
        $categoryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $repositoryCategory->deleteCategory($categoryId);
        $this->request->getSession()->setAttribut('flash', ['alert' => "Catégorie supprimer avec succès"]);
        header('Location: /dashboard/articleManagement');
        exit;
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