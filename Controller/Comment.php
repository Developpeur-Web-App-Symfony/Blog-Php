<?php
namespace Controller;

use Exception;
use Framework\Controller;

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
        $comment = new \Model\Comment();
        $comment->setStatus(Controller::IS_VALID['NO_VALID']);
        $repositoryComment = new \Repository\Comment($comment);
        $allComment = $repositoryComment->getAllComments();

        $this->generateView([
            'allComments' => $allComment ?? null
        ]);
    }

    public function valid()
    {
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
        $comment = new \Model\Comment();
        $commentId = $this->request->getParameter('id');
        $comment->setId($commentId);
        $repositoryComment = new \Repository\Comment($comment);
        $repositoryComment->deleteComment();
        $this->request->getSession()->setAttribut('flash', ['alert' => "Commentaire supprimer avec succès"]);
        header('Location: /comment/commentManagement');
        exit;
    }
}