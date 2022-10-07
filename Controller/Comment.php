<?php
namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;

class Comment extends \Framework\Controller
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
    public function commentManagement()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        $comment = new \Model\Comment();
        $comment->setStatus(Controller::IS_VALID['NO_VALID']);
        $repositoryComment = new \Repository\Comment($comment);
        $allComment = $repositoryComment->getAllComments();

        $token = Session::getSession()->getToken();

        $this->generateView([
            'allComments' => $allComment ?? null,
            'token' => $token,
        ]);
    }

    public function valid()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        $comment = new \Model\Comment();
        $repositoryComment = new \Repository\Comment($comment);
        $commentId = $this->request->getParameter('id');
        $comment->setId($commentId);
        $comment->setStatus(Controller::IS_VALID['VALID']);
        $repositoryComment->updateComment();
        $this->request->getSession()->setAttribut('flash', ['alert' => "Commentaire valider avec succès"]);
        header('Location: /comment/commentManagement');
        exit;
    }

    public function delete()
    {
        if (intval(Session::getSession()->getRoleLevel()) < Controller::AUTHOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès a cette page"]);
            header("Location: /home/index");
            exit();
        }
        $tokenUser = Session::getSession()->getToken();
        if ($this->request->existsParameter('deleteCommentId') && $this->request->existsParameter('commentToken')) {
            if ($this->request->getParameter('commentToken') == $tokenUser) {
                $comment = new \Model\Comment();
                $commentId = $this->request->getParameter('deleteCommentId');
                $comment->setId($commentId);
                $repositoryComment = new \Repository\Comment($comment);
                $repositoryComment->deleteComment();
                $this->request->getSession()->setAttribut('flash', ['alert' => "Commentaire supprimer avec succès"]);
            } else {
                $this->request->getSession()->setAttribut('flash', ['alert' => "Une erreur est survenue, veuillez réessayer"]);
            }
            header('Location: /comment/commentManagement');
            exit;
        }
    }
}