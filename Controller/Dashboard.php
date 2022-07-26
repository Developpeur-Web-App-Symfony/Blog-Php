<?php
namespace Controller;

use Exception;
use Model\ArticleAsCategory;
use Repository\Category;
use Services\Upload;
use Services\ValidatorAddArticle;

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

        $repositoryArticle = new \Repository\Article();
        $allArticle = $repositoryArticle->getAllArticles();
        var_dump($allArticle);die();

        $this->generateView([
            'allArticle' => $allArticle,
        ]);
    }

    public function createArticle()
    {
        $repositoryCategory = new Category();
        $allCategory =$repositoryCategory->getAllCategory();

        if ($this->request->existsParameter('saveArticle')) {
            if ($this->request->existsParameter('saveArticle') == 'save') {

                $article = new \Model\Article();

                $category = new \Model\Category();
                $articleAsCategory = new ArticleAsCategory();
                $userid = $this->request->getSession()->getSession()->getId();
                $article->setUserId($userid);
                $article->setTitle($this->request->getParameter('title'));
                $category->setId($this->request->getParameter('category'));
                $article->setContent($this->request->getParameter('content'));
                $article->setExcerpt($this->request->getParameter('excerpt'));
                $article->setImageFilename($_FILES['file']['name']);
                $article->setImageAlt($this->request->getParameter('alt'));
                $validator = new ValidatorAddArticle($article, $category);
                $path = \Framework\Controller::PATH_UPLOAD;
                if ($validator->checkImagePresent()) {
                    if ($validator->checkImageUpload()) {
                        Upload::uploadPicture($article, $path);
                    }
                } else{
                    $article->setImageFilename(\Framework\Controller::IMAGE_DEFAULT['NAME']);
                    $article->setImageAlt(\Framework\Controller::IMAGE_DEFAULT['ALT']);
                    Upload::uploadPicture($article, $path);
                }
                if ($validator->formAddArticleValidate()) {
                    if ($this->request->getParameter('publishArticle')) {
                        $article->setPublish(\Framework\Controller::PUBLISH['PUBLISH']);
                    } else{
                        $article->setPublish(\Framework\Controller::PUBLISH['DRAFT']);
                    }
                    $repositoryArticle = new \Repository\Article();
                    $repositoryArticle->save($article);
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Article créer avec succès"]);
                    header('Location: articleManagement');
                    exit();
                } else {
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Veuillez verifier les champs"]);
                    header('Location: createArticle');
                    exit();
                }
            }
        }

        $this->generateView([
            'allCategory' => $allCategory,
            'validator' => $validator ?? null,
            'article' => $article ?? null
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