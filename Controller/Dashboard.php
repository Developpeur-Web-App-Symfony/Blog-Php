<?php
namespace Controller;

use App\Framework\Controller;
use Exception;
use Model\ArticleAsCategory;
use Repository\Category;
use Services\Upload;
use Services\Validator;
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
                if ($validator->formAddArticleValidate())
                {

                    if ($validator->checkImagePresent())
                    {
                        if ($validator->imageUpload())
                        {
                            $path = \Framework\Controller::PATH_UPLOAD;
                            Upload::uploadPicture(null, $path);
                        }
                    }

                }





            }
        }
//        Récupération des données du form
//        Création de l'objet Article de la class
//        Enregistrer l'id du User connecter dans l'article user_id à partir de request
//        hydratation de l'objet
//        appel du validateur
//        passage de l'objet au validateur
//        si objet valider => appel du service Upload
//        Si upload correct => appel du repository Article
//        Enregistrement en bdd et affichage du succès

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