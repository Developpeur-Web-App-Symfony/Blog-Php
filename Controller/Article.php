<?php

namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;
use Model\User;
use Repository\ArticleHasCategory;
use Repository\Category;
use Services\ValidatorAddArticle;
use Services\ValidatorAddComment;
use Services\ValidatorUpload;

class Article extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {
        $repositoryArticle = new \Repository\Article();
        $allArticle = $repositoryArticle->getAllArticles(Controller::PUBLISH['PUBLISH']);

        $this->generateView([
            'allArticle' => $allArticle ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function read()
    {

        $articleId = $this->request->getParameter('id');
        $article = new \Repository\Article();
        $articleDetails = $article->getArticle($articleId);
        $comment = new \Model\Comment();
        $repositoryComment = new \Repository\Comment($comment);
        $commentBdd = $repositoryComment->getCommentsArticle($articleId);

        $roleLevel = Session::getSession()->getRoleLevel();

        if ($roleLevel >= Controller::USER) {
            $userId = Session::getSession()->getId();
            $user = new User();
            $userRepository = new \Repository\User($user);
            $userBdd = $userRepository->getUser($userId);
            if ($this->request->existsParameter('commentForm')) {
                if ($this->request->existsParameter('commentForm') == 'comment') {
                    $comment->setUserId($userId);
                    $comment->setContent($this->request->getParameter('content'));
                    $validatorComment = new ValidatorAddComment($comment);
                    if ($validatorComment->formAddCommentValidate()) {
                        $dateComment = new \DateTime();
                        $comment->setCreatedAt($dateComment->format('Y-m-d H:i:s'));
                        $comment->setArticleId($articleId);
                        $comment->setStatus(Controller::IS_VALID['NO_VALID']);
                        $repositoryComment->save();
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Votre commentaire à bien été poster, il sera publié après validation"]);
                        header("Location: /article/read/$articleId");
                        exit();
                    }
                }
            }
        }
        $this->generateView([
            'article' => $articleDetails ?? null,
            'user' => $userBdd ?? null,
            'roleLevel' => $roleLevel,
            'comments' => $commentBdd ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $allCategory = $repositoryCategory->getAllCategory();

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
                    } else {
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
                    } else {
                        $article->setImageFilename(\Framework\Controller::IMAGE_DEFAULT['NAME']);
                        $article->setImageAlt(\Framework\Controller::IMAGE_DEFAULT['ALT']);
                        $repositoryArticle->save($article);
                        //                    ENREGISTREMENT DE LA CATEGORIE EN BDD
                        $articleId = $repositoryArticle->getLastIdArticle();
                        $repositoryArticleHasCategory = new ArticleHasCategory();
                        $categoryId = $category->getId();
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


    /**
     * @throws Exception
     */
    public function update()
    {
        $articleId = $this->request->getParameter('id');
        $repositoryArticle = new \Repository\Article();
        $article = $repositoryArticle->getArticle($articleId);
        $category = new \Model\Category();
        $repositoryCategory = new Category($category);
        $allCategory = $repositoryCategory->getAllCategory();
        $repositoryArticleHasCategory = new \Repository\ArticleHasCategory();
        $articleHasCategoryList = $repositoryArticleHasCategory->getCategoriesHasArticle($articleId);
        $user = new User();
        $repositoryUser = new \Repository\User($user);
        $listUsers = $repositoryUser->getUsers(Controller::AUTHOR, Controller::IS_VALID['VALID']);

        if ($this->request->existsParameter('saveArticle')) {
            if ($this->request->existsParameter('saveArticle') == 'save') {
                $article = new \Model\Article();
                $newModification = new \DateTime();
                $article->setUpdatedAt($newModification->format('Y-m-d H:i:s'));
                $article->setTitle($this->request->getParameter('title'));
                $categoryList = $this->request->getParameter('category');
                $category->setId($categoryList);
                $article->setContent($this->request->getParameter('content'));
                $article->setExcerpt($this->request->getParameter('excerpt'));
                $article->setImageFilename($this->request->getParameter('file')['name']);
                $article->setImageAlt($this->request->getParameter('alt'));
                $article->setId($articleId);
                $userId = implode("", $this->request->getParameter('author'));
                $article->setUserId($userId);
                $validator = new ValidatorAddArticle($article, $category);
                $validatorUpload = new ValidatorUpload($article);
                if ($validator->formAddArticleValidate() && $validator->checkAuthorSelected()) {
                    if ($this->request->getParameter('updateArticle')) {
                        $article->setPublish(\Framework\Controller::PUBLISH['PUBLISH']);
                    } else {
                        $article->setPublish(\Framework\Controller::PUBLISH['DRAFT']);
                    }
                    $categoryIdInBdd = [];
                    foreach ($articleHasCategoryList as $item) {
                        $categoryIdInBdd[] = $item->getCategoriesId();
                    }
                    if ($validator->checkImagePresent()) {
                        if ($validator->checkImageUpload()) {
                            if ($validatorUpload->checkErrorFile()) {
                                if ($validatorUpload->formUploadValidate()) {
                                    $validatorUpload->uploadFile();
                                    $repositoryArticle->update($article);
                                    //Suppression des associations de categories aux articles avant enregistrement des nouvelles
                                    $repositoryArticleHasCategory->deleteCategoryHasArticle($categoryIdInBdd, $articleId);
                                    $repositoryArticleHasCategory->save($categoryList, $articleId);
                                    $this->request->getSession()->setAttribut('flash', ['alert' => "Article modifier avec succès"]);
                                    header('Location: /dashboard/articleManagement');
                                    exit();
                                }
                            }
                        }
                    } else {
                        $article->setImageFilename(\Framework\Controller::IMAGE_DEFAULT['NAME']);
                        $article->setImageAlt(\Framework\Controller::IMAGE_DEFAULT['ALT']);

                        $repositoryArticle->update($article);

                        //                    ENREGISTREMENT DE LA CATEGORIE EN BDD
//Suppression des associations de categories aux articles avant enregistrement des nouvelles
                        $repositoryArticleHasCategory->deleteCategoryHasArticle($categoryIdInBdd, $articleId);
                        $repositoryArticleHasCategory->save($categoryList, $articleId);
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Article modifier avec succès"]);
                        header('Location: /dashboard/articleManagement');
                        exit();
                    }

                }
            }
        }
        $this->generateView([
            'article' => $article,
            'validator' => $validator ?? null,
            'validatorUpload' => $validatorUpload ?? null,
            'allCategory' => $allCategory,
            'articleHasCategory' => $articleHasCategoryList,
            'listUsers' => $listUsers ?? null,
        ]);
    }

    public function delete()
    {
        $articleId = $this->request->getParameter('id');
        $repositoryArticle = new \Repository\Article();
        $repositoryArticle->deleteArticle($articleId);
        $this->request->getSession()->setAttribut('flash', ['alert' => "Article supprimer avec succès"]);
        header('Location: /dashboard/articleManagement');
        exit;

    }

}